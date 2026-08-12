<?php

namespace App\Services;

use App\Models\MailSetting;
use App\Models\SentEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class MailService
{
    public function send(string $category, string $toEmail, ?string $toName, string $subject, string $body, ?string $relatedType = null, ?int $relatedId = null, ?int $userId = null): array
    {
        $toEmail = trim($toEmail);
        if ($toEmail === '') {
            return ['success' => false, 'error' => 'Recipient email is empty', 'status' => 'failed'];
        }

        $settings = MailSetting::current();
        $settings->applyToConfig();

        $fromAddress = $settings->from_address ?: config('mail.from.address');
        $fromName = $settings->from_name ?: config('mail.from.name');

        $log = SentEmail::create([
            'category' => $category,
            'subject' => $subject,
            'to_email' => $toEmail,
            'to_name' => $toName,
            'from_email' => $fromAddress,
            'from_name' => $fromName,
            'related_type' => $relatedType,
            'related_id' => $relatedId,
            'sent_by' => $userId,
            'body' => $body,
            'status' => 'queued',
        ]);

        try {
            Mail::raw($body, function ($message) use ($toEmail, $toName, $subject, $fromAddress, $fromName, $settings) {
                $message->to($toEmail, $toName ?: null)
                    ->subject($subject);
                if ($fromAddress) {
                    $message->from($fromAddress, $fromName);
                }
                if ($settings->reply_to_address) {
                    $message->replyTo($settings->reply_to_address, $settings->reply_to_name ?: $settings->reply_to_address);
                }
            });
            $log->update(['status' => 'sent', 'sent_at' => now()]);
            return ['success' => true, 'log' => $log];
        } catch (Throwable $e) {
            $log->update(['status' => 'failed', 'error' => $e->getMessage()]);
            Log::error('MailService send failed', [
                'category' => $category,
                'to' => $toEmail,
                'error' => $e->getMessage(),
            ]);
            return ['success' => false, 'error' => $e->getMessage(), 'log' => $log];
        }
    }

    public function recipientsForQuotation(int $quotationId): array
    {
        $quotation = \DB::table('quotations')->where('id', $quotationId)->first();

        if (! $quotation) {
            return [];
        }

        $client = \DB::table('clients')->where('id', $quotation->client_id)->first();

        $recipients = [];
        if ($client && !empty($client->email)) {
            $recipients[] = ['email' => $client->email, 'name' => $client->name];
        }

        $lead = \DB::table('leads')->where('email', optional($client)->email)->first();
        if ($lead && !empty($lead->email)) {
            $exists = false;
            foreach ($recipients as $r) if ($r['email'] === $lead->email) { $exists = true; break; }
            if (! $exists) $recipients[] = ['email' => $lead->email, 'name' => $lead->name ?? null];
        }

        return $recipients;
    }

    public function templateFor(string $category, array $context): array
    {
        $clientName = $context['client_name'] ?? 'Traveller';
        $quotation = $context['quotation_reference'] ?? 'Q-';
        $currency = $context['currency'] ?? 'USD';
        $amount = $context['amount'] ?? 'TBA';
        $startDate = $context['start_date'] ?? 'To be confirmed';
        $company = $context['company'] ?? 'Shishi Footsteps';

        return match ($category) {
            'ready_to_book' => [
                'subject' => "Ready to book – {$company} (Ref {$quotation})",
                'body' => "Hello {$clientName},\n\nThank you for confirming the details of your safari. We are ready to turn your proposal into a confirmed booking.\n\nQuotation: {$quotation}\nTravel start date: {$startDate}\nTotal due: {$currency} {$amount}\n\nTo lock in the accommodation, transfers, park fees and guides, please confirm your booking by replying to this email and processing the deposit. Our reservations team will then confirm supplier availability and issue your final vouchers.\n\nIf any details need to change, simply reply and we will adjust the proposal accordingly.\n\nWarm regards,\n{$company} Team",
            ],
            'pre_confirmation' => [
                'subject' => "Pre-confirmation pending – {$company} (Ref {$quotation})",
                'body' => "Hello {$clientName},\n\nWe have submitted the reservation requests for your safari ({$quotation}). Our suppliers are reviewing availability and we expect the formal confirmation within 24–48 hours.\n\nTravel start date: {$startDate}\nTotal value: {$currency} {$amount}\n\nYou will receive a separate confirmation email once every service has been confirmed by the lodges, operators and parks. Please reply if there are any changes in the meantime.\n\nThank you for your patience.\n\nWarm regards,\n{$company} Team",
            ],
            'confirmation' => [
                'subject' => "Booking confirmed – {$company} (Ref {$quotation})",
                'body' => "Hello {$clientName},\n\nYour safari with {$company} is officially confirmed. Quote reference {$quotation}.\n\nTravel start date: {$startDate}\nTotal confirmed value: {$currency} {$amount}\n\nYour final itinerary, vouchers and emergency contact numbers are attached to this email. Please review them and let us know if anything needs to be changed before departure.\n\nThank you for choosing {$company}. We cannot wait to host you on safari.\n\nWarm regards,\n{$company} Team",
            ],
            default => [
                'subject' => 'Shishi Footsteps update',
                'body' => "Hello {$clientName},\n\nThis is a quick update from {$company} regarding reference {$quotation}.\n\nWarm regards,\n{$company} Team",
            ],
        };
    }
}
