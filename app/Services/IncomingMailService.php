<?php

namespace App\Services;

use App\Models\IncomingEmail;
use App\Models\IncomingMailAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class IncomingMailService
{
    /**
     * Fetch all unread messages from every active account.
     */
    public function fetchAll(): array
    {
        $stats = ['accounts' => 0, 'fetched' => 0, 'errors' => 0];
        foreach (IncomingMailAccount::where('is_active', true)->get() as $account) {
            $stats['accounts']++;
            $result = $this->fetchAccount($account);
            $stats['fetched'] += $result['fetched'];
            $stats['errors'] += $result['error'] ? 1 : 0;
        }
        return $stats;
    }

    /**
     * Fetch new messages from a single account.
     */
    public function fetchAccount(IncomingMailAccount $account): array
    {
        $result = ['fetched' => 0, 'error' => null];

        if (! function_exists('imap_open')) {
            $result['error'] = 'PHP imap extension is not loaded.';
            $account->update(['error' => $result['error']]);
            return $result;
        }

        $mailboxString = $this->mailboxString($account);

        try {
            $connection = @imap_open($mailboxString, $account->username, $account->password, 0, 1, [
                'DISABLE_AUTHENTICATOR' => 'GSSAPI',
            ]);
        } catch (Throwable $e) {
            $result['error'] = $e->getMessage();
        }

        if (! $connection ?? false) {
            $result['error'] = $result['error'] ?? imap_last_error() ?: 'Failed to connect.';
            $account->update(['error' => $result['error']]);
            return $result;
        }

        try {
            $uids = imap_search($connection, 'UNSEEN', SE_UID);
            if (! $uids) {
                $uids = [];
            }
            $uids = is_array($uids) ? $uids : [];

            // Also include any UNSEEN+SEEN messages with UID greater than last_uid (recovery)
            $allUids = imap_search($connection, 'ALL', SE_UID);
            $allUids = is_array($allUids) ? array_values($allUids) : [];
            if ($account->last_uid) {
                foreach ($allUids as $uid) {
                    if ($uid > $account->last_uid && ! in_array($uid, $uids, true)) {
                        $uids[] = $uid;
                    }
                }
                $uids = array_values(array_unique($uids));
            }

            $maxUid = $account->last_uid ?? 0;

            foreach ($uids as $uid) {
                try {
                    $message = $this->buildMessage($connection, $uid);
                    if (! $message) {
                        continue;
                    }

                    $existing = IncomingEmail::where('message_id', $message['message_id'])->first();
                    if ($existing) {
                        continue;
                    }

                    IncomingEmail::create([
                        'account_id' => $account->id,
                        'message_id' => $message['message_id'],
                        'uid' => $uid,
                        'from_email' => $message['from_email'],
                        'from_name' => $message['from_name'],
                        'to_email' => $message['to_email'],
                        'subject' => $message['subject'],
                        'body_text' => $message['body_text'],
                        'body_html' => $message['body_html'],
                        'headers' => $message['headers'],
                        'received_at' => $message['received_at'],
                        'status' => 'new',
                    ]);

                    $result['fetched']++;

                    if ($account->delete_after_fetch) {
                        imap_delete($connection, $uid);
                    } elseif ($account->mark_seen) {
                        imap_setflag_full($connection, (string) $uid, '\\Seen', SE_UID);
                    }

                    if ($uid > $maxUid) {
                        $maxUid = $uid;
                    }
                } catch (Throwable $e) {
                    Log::warning('Incoming mail fetch failed for one message', [
                        'account_id' => $account->id,
                        'uid' => $uid,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            if ($account->delete_after_fetch) {
                imap_expunge($connection);
            }

            $account->update([
                'last_fetched_at' => now(),
                'last_uid' => $maxUid,
                'error' => null,
            ]);
        } finally {
            @imap_close($connection);
        }

        return $result;
    }

    protected function buildMessage($connection, int $uid): ?array
    {
        $header = imap_headerinfo($connection, $uid, 0, SE_UID);
        if (! $header) {
            return null;
        }

        $fromEmail = null;
        $fromName = null;
        $toEmail = null;
        if (! empty($header->from[0])) {
            $fromEmail = $header->from[0]->mailbox && $header->from[0]->host
                ? $header->from[0]->mailbox.'@'.$header->from[0]->host
                : null;
            $fromName = $header->from[0]->personal ?? null;
        }
        if (! empty($header->to[0])) {
            $toEmail = $header->to[0]->mailbox && $header->to[0]->host
                ? $header->to[0]->mailbox.'@'.$header->to[0]->host
                : null;
        }

        $structure = imap_fetchstructure($connection, $uid, FT_UID);
        $bodyText = null;
        $bodyHtml = null;
        $this->extractBodies($connection, $uid, $structure, '', $bodyText, $bodyHtml);

        $messageId = $header->message_id ?? null;
        $subject = $header->subject ?? null;
        $receivedAt = isset($header->date) ? @strtotime($header->date) : null;
        $receivedAt = $receivedAt ? date('Y-m-d H:i:s', $receivedAt) : now();

        $rawHeaders = $this->extractHeaders($connection, $uid);

        return [
            'message_id' => $messageId,
            'from_email' => $fromEmail,
            'from_name' => $fromName,
            'to_email' => $toEmail,
            'subject' => $subject ? trim(preg_replace('/\s+/', ' ', $header->Subject ?? $subject)) : null,
            'body_text' => $bodyText,
            'body_html' => $bodyHtml,
            'headers' => $rawHeaders,
            'received_at' => $receivedAt,
        ];
    }

    protected function extractBodies($connection, int $uid, $structure, string $prefix, ?string &$text, ?string &$html): void
    {
        if (! $structure || ! isset($structure->parts)) {
            $body = imap_body($connection, $uid, FT_UID);
            if ($structure->type === 0 && $text === null) {
                $text = $this->decodePart($body, $structure->encoding);
            } elseif ($structure->type === 1 && $text === null && $body) {
                $text = $this->decodePart($body, $structure->encoding);
            } elseif ($structure->type === 0 && $text === null) {
                $text = $this->decodePart($body, $structure->encoding);
            }
            if ($structure->subtype === 'HTML' && $html === null) {
                $html = $this->decodePart($body, $structure->encoding);
            }
            if (($structure->subtype === 'PLAIN' || $structure->type === 0) && $text === null) {
                $text = $this->decodePart($body, $structure->encoding);
            }
            return;
        }

        foreach ($structure->parts as $i => $part) {
            $partNumber = $prefix === '' ? (string) ($i + 1) : $prefix.'.'.($i + 1);
            if (isset($part->parts)) {
                $this->extractBodies($connection, $uid, $part, $partNumber, $text, $html);
                continue;
            }
            $body = imap_fetchbody($connection, $uid, $partNumber, FT_UID);
            $decoded = $this->decodePart($body, $part->encoding ?? 0);

            if ($part->subtype === 'HTML' && $html === null) {
                $html = $decoded;
            } elseif ($part->subtype === 'PLAIN' && $text === null) {
                $text = $decoded;
            } elseif ($text === null) {
                $text = $decoded;
            }
        }
    }

    protected function decodePart(string $body, int $encoding): string
    {
        return match ($encoding) {
            3 => base64_decode($body) ?: $body,
            4 => quoted_printable_decode($body),
            default => $body,
        };
    }

    protected function extractHeaders($connection, int $uid): array
    {
        $raw = imap_fetchheader($connection, $uid, FT_UID);
        $headers = [];
        if (! $raw) return $headers;
        foreach (preg_split("/\r?\n/", $raw) as $line) {
            if (preg_match('/^([A-Za-z0-9\-]+):\s*(.*)$/', $line, $matches)) {
                $headers[strtolower($matches[1])] = trim($matches[2]);
            }
        }
        return $headers;
    }

    protected function mailboxString(IncomingMailAccount $account): string
    {
        $enc = $account->encryption === 'none' ? '' : '/'.($account->encryption === 'ssl' ? 'ssl' : 'tls');
        $valid = '/validate-cert';
        $novalidate = '/novalidate-cert';
        $mark = $account->mark_seen ? '/read-only' : '';
        return sprintf('{%s:%d%s%s%s}%s', $account->host, $account->port, $enc, $novalidate, $mark, $account->folder ?: 'INBOX');
    }

    /**
     * Convert an incoming email into a Request (admin can also convert to Lead).
     */
    public function convertToRequest(IncomingEmail $email, ?int $consultantId = null): int
    {
        $data = [
            'request_number' => \App\Models\Request::generateRequestNumber(),
            'request_date' => ($email->received_at ?: now())->toDateString(),
            'client_name' => $email->from_name ?: $email->from_email ?: 'Email enquiry',
            'client_email' => $email->from_email,
            'client_phone' => null,
            'country' => null,
            'destination' => null,
            'arrival_date' => null,
            'adults' => null,
            'children' => null,
            'nights' => null,
            'budget' => null,
            'currency' => 'USD',
            'source' => 'email',
            'language' => 'en',
            'priority' => 'medium',
            'status' => 'new',
            'travel_type' => 'safari',
            'internal_notes' => $this->emailSummary($email),
            'special_requests' => $email->body_text ?: strip_tags($email->body_html ?: ''),
            'assigned_consultant_id' => $consultantId ?? $email->account?->assigned_consultant_id,
            'assigned_to' => $consultantId ?? $email->account?->assigned_consultant_id,
        ];

        $request = \App\Models\Request::create($data);
        $request->logStatus(null, 'new', 'Created from incoming email #'.$email->id);
        $email->update([
            'status' => 'converted_to_request',
            'request_id' => $request->id,
            'assigned_to' => $data['assigned_consultant_id'] ?? null,
        ]);
        return $request->id;
    }

    public function convertToLead(IncomingEmail $email, ?int $consultantId = null): int
    {
        $lead = \App\Models\Lead::create([
            'name' => $email->from_name ?: $email->from_email ?: 'Email enquiry',
            'email' => $email->from_email,
            'source' => 'email',
            'status' => 'new',
            'assigned_consultant_id' => $consultantId ?? $email->account?->assigned_consultant_id,
            'destination' => null,
            'travel_date' => $email->received_at,
            'travelers' => null,
            'notes' => $this->emailSummary($email),
        ]);
        $email->update([
            'status' => 'converted_to_lead',
            'lead_id' => $lead->id,
            'assigned_to' => $consultantId ?? $email->account?->assigned_consultant_id,
        ]);
        return $lead->id;
    }

    public function ignore(IncomingEmail $email): void
    {
        $email->update(['status' => 'ignored']);
    }

    protected function emailSummary(IncomingEmail $email): string
    {
        $lines = [
            'Source: '.($email->account?->label ?: 'IMAP'),
            'From: '.trim(($email->from_name ?: '').' <'.$email->from_email.'>'),
            'To: '.($email->to_email ?: ''),
            'Subject: '.($email->subject ?: ''),
            'Received: '.($email->received_at ? $email->received_at->toDateTimeString() : ''),
            '',
            'Original message:',
            $email->body_text ?: strip_tags($email->body_html ?: ''),
        ];
        return implode("\n", $lines);
    }
}
