<?php

namespace App\Services;

use App\Models\EvaluationEntry;
use App\Models\ProposalEvaluation;
use App\Models\SupplierInvoice;
use App\Models\User;
use App\Notifications\EvaluationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Throwable;

class EvaluationService
{
    public function syncEntries(int $quotationId): void
    {
        $items = DB::table('quotation_items')
            ->join('quotation_days', 'quotation_days.id', '=', 'quotation_items.quotation_day_id')
            ->leftJoin('reservations', 'reservations.quotation_item_id', '=', 'quotation_items.id')
            ->select(
                'quotation_items.*',
                'quotation_days.quotation_id',
                'quotation_days.travel_date',
                'reservations.id as reservation_id',
                'reservations.starts_at',
                'reservations.ends_at',
            )
            ->where('quotation_days.quotation_id', $quotationId)
            ->get();

        foreach ($items as $item) {
            $entry = EvaluationEntry::firstOrNew(['quotation_item_id' => $item->id]);
            $startsAt = $item->starts_at ? substr($item->starts_at, 0, 10) : $item->travel_date;
            $endsAt = $item->ends_at ? substr($item->ends_at, 0, 10) : $item->travel_date;

            $entry->fill([
                'quotation_id' => $quotationId,
                'quotation_day_id' => $item->quotation_day_id,
                'reservation_id' => $item->reservation_id,
                'item_type' => $item->item_type,
                'title' => $item->title,
                'supplier' => $item->source,
                'service_date' => $startsAt,
                'service_end_date' => $endsAt,
                'arrival_date' => $startsAt,
                'departure_date' => $endsAt,
                'system_rate' => $item->buy_total,
                'quantity' => $item->quantity,
            ]);
            if (!$entry->exists) {
                $entry->status = 'missing_invoice';
            }
            $entry->save();
        }

        ProposalEvaluation::firstOrCreate(
            ['quotation_id' => $quotationId],
            ['status' => 'pending']
        );

        $this->refreshEvaluationSummary($quotationId);
    }

    public function refreshEvaluationSummary(int $quotationId): void
    {
        $entries = EvaluationEntry::where('quotation_id', $quotationId)->get();
        $eval = ProposalEvaluation::where('quotation_id', $quotationId)->first();
        if (!$eval) return;

        $eval->update([
            'total_entries' => $entries->count(),
            'matched_entries' => $entries->where('status', 'matched')->count(),
            'missing_invoices' => $entries->where('status', 'missing_invoice')->count(),
            'total_variance' => $entries->sum('discrepancy'),
        ]);
    }

    public function assignInvoice(int $entryId, int $invoiceId, ?float $allocatedAmount = null, int $userId): void
    {
        $entry = EvaluationEntry::findOrFail($entryId);
        $invoice = SupplierInvoice::findOrFail($invoiceId);

        if ((int) $invoice->quotation_id !== (int) $entry->quotation_id) {
            throw new \InvalidArgumentException('Invoice does not belong to this proposal.');
        }

        $amount = $allocatedAmount ?? $invoice->amount;

        $entry->update([
            'supplier_invoice_id' => $invoiceId,
            'invoice_rate' => $amount,
            'allocated_amount' => $amount,
            'evaluated_by' => $userId,
            'evaluated_at' => now(),
            'status' => 'evaluated',
        ]);

        $this->updateVariance($entry);

        if ($invoice->is_split_invoice && $allocatedAmount) {
            $remaining = max(0, ($invoice->remaining_balance ?? $invoice->amount) - $allocatedAmount);
            $invoice->update(['remaining_balance' => $remaining]);
        }

        $this->refreshInvoiceStatus($invoiceId, $userId);
        $this->refreshEvaluationSummary((int) $entry->quotation_id);
    }

    public function splitInvoice(int $invoiceId, array $splits, int $userId): array
    {
        $invoice = SupplierInvoice::findOrFail($invoiceId);
        $totalAllocated = array_sum(array_column($splits, 'amount'));

        if ($totalAllocated > $invoice->amount) {
            throw new \InvalidArgumentException('Split amounts exceed invoice total.');
        }

        $invoice->update([
            'is_split_invoice' => true,
            'remaining_balance' => $invoice->amount - $totalAllocated,
        ]);

        $children = [];
        foreach ($splits as $i => $split) {
            $child = SupplierInvoice::create([
                'quotation_id' => $invoice->quotation_id,
                'reservation_id' => $invoice->reservation_id,
                'uploaded_by' => $userId,
                'invoice_date' => $invoice->invoice_date,
                'invoice_number' => $invoice->invoice_number . '-' . ($i + 1),
                'company_name' => $invoice->company_name,
                'amount' => $split['amount'],
                'currency' => $invoice->currency,
                'invoice_type' => $invoice->invoice_type,
                'is_split_invoice' => true,
                'parent_invoice_id' => $invoice->id,
                'status' => 'recorded',
            ]);
            $children[] = $child;

            if (!empty($split['entry_id'])) {
                $this->assignInvoice((int) $split['entry_id'], (int) $child->id, (float) $split['amount'], $userId);
            }
        }

        return $children;
    }

    public function updateVariance(EvaluationEntry $entry): void
    {
        $sysRate = (float) $entry->system_rate;
        $invRate = (float) ($entry->invoice_rate ?? $entry->allocated_amount ?? 0);
        $discrepancy = $invRate - $sysRate;
        $variancePct = $sysRate > 0 ? round(($discrepancy / $sysRate) * 100, 2) : 0;

        $entry->update([
            'discrepancy' => $discrepancy,
            'variance_percent' => $variancePct,
            'is_overcharge' => $discrepancy > 0,
            'is_undercharge' => $discrepancy < 0,
            'is_mismatch' => abs($variancePct) > 5,
        ]);
    }

    public function checkDuplicateInvoice(string $invoiceNumber, int $quotationId): ?SupplierInvoice
    {
        return SupplierInvoice::where('invoice_number', $invoiceNumber)
            ->where('quotation_id', $quotationId)
            ->first();
    }

    public function findMissingInvoices(int $quotationId): array
    {
        $entries = EvaluationEntry::where('quotation_id', $quotationId)
            ->where('status', 'missing_invoice')
            ->get();

        $grouped = [
            'accommodation' => $entries->filter(fn($e) => in_array($e->item_type, ['room', 'accommodation', 'hotel'])),
            'activities' => $entries->filter(fn($e) => $e->item_type === 'activity'),
            'transport' => $entries->filter(fn($e) => $e->item_type === 'transport'),
            'jeep' => $entries->filter(fn($e) => $e->item_type === 'vehicle' || str_contains($e->item_type, 'jeep')),
            'guide' => $entries->filter(fn($e) => $e->item_type === 'guide'),
            'supplements' => $entries->filter(fn($e) => $e->item_type === 'supplement'),
            'park_fees' => $entries->filter(fn($e) => in_array($e->item_type, ['park_fee', 'park_fees', 'fee'])),
            'misc' => $entries->filter(fn($e) => !in_array($e->item_type, [
                'room', 'accommodation', 'hotel', 'activity', 'transport',
                'vehicle', 'guide', 'supplement', 'park_fee', 'park_fees', 'fee',
            ])),
        ];

        $summary = [
            'accommodation' => ['total' => $grouped['accommodation']->count(), 'items' => $grouped['accommodation']],
            'activities' => ['total' => $grouped['activities']->count(), 'items' => $grouped['activities']],
            'transport' => ['total' => $grouped['transport']->count(), 'items' => $grouped['transport']],
            'jeep' => ['total' => $grouped['jeep']->count(), 'items' => $grouped['jeep']],
            'guide' => ['total' => $grouped['guide']->count(), 'items' => $grouped['guide']],
            'supplements' => ['total' => $grouped['supplements']->count(), 'items' => $grouped['supplements']],
            'park_fees' => ['total' => $grouped['park_fees']->count(), 'items' => $grouped['park_fees']],
            'misc' => ['total' => $grouped['misc']->count(), 'items' => $grouped['misc']],
        ];

        return $summary;
    }

    public function notifyRoles(array $roles, string $type, string $message, int $quotationId, string $severity = 'info'): void
    {
        try {
            $users = User::where('is_active', true)->whereIn('role', $roles)->get();
            Notification::send($users, new EvaluationNotification($type, $message, $quotationId, $severity));

            DB::table('evaluation_notifications')->insert([
                'quotation_id' => $quotationId,
                'type' => $type,
                'severity' => $severity,
                'message' => $message,
                'sent_to' => json_encode($users->pluck('id')->toArray()),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (Throwable $e) {
            logger()->warning('Evaluation notification failed', ['error' => $e->getMessage()]);
        }
    }

    public function logAudit(string $action, string $description, ?int $quotationId, ?int $invoiceId, ?int $entryId, int $userId, ?array $old = null, ?array $new = null): void
    {
        DB::table('evaluation_audit_logs')->insert([
            'quotation_id' => $quotationId,
            'supplier_invoice_id' => $invoiceId,
            'evaluation_entry_id' => $entryId,
            'user_id' => $userId,
            'action' => $action,
            'module' => 'evaluation',
            'description' => $description,
            'old_values' => $old ? json_encode($old) : null,
            'new_values' => $new ? json_encode($new) : null,
            'ip_address' => request()->ip(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function validateInvoiceUpload(array $data, int $quotationId): array
    {
        $errors = [];

        if (empty($data['invoice_number'])) {
            $errors[] = 'Invoice number is required.';
        }

        if (empty($data['company_name'])) {
            $errors[] = 'Supplier/company name is required.';
        }

        if (($data['amount'] ?? 0) <= 0) {
            $errors[] = 'Invoice amount must be greater than zero.';
        }

        if (empty($data['invoice_date'])) {
            $errors[] = 'Invoice date is required.';
        }

        $duplicate = $this->checkDuplicateInvoice($data['invoice_number'], $quotationId);
        if ($duplicate) {
            $errors[] = "Duplicate invoice number '{$data['invoice_number']}' already exists for this proposal ({$duplicate->company_name}).";
        }

        $vatRate = $data['vat_rate'] ?? 0;
        if ($vatRate < 0 || $vatRate > 100) {
            $errors[] = 'VAT rate must be between 0 and 100.';
        }

        if (isset($data['currency']) && strlen($data['currency']) !== 3) {
            $errors[] = 'Currency must be a 3-letter code (e.g. USD, EUR).';
        }

        return $errors;
    }

    private function refreshInvoiceStatus(int $invoiceId, int $userId): void
    {
        $invoice = SupplierInvoice::find($invoiceId);
        if (!$invoice) return;

        $entries = EvaluationEntry::where('supplier_invoice_id', $invoiceId)->get();
        $hasIssue = $entries->contains(fn($e) => $e->status === 'issue');
        $allMatched = $entries->isNotEmpty() && $entries->every(fn($e) => $e->status === 'matched');
        $protected = in_array($invoice->status, ['approved', 'payment_ready', 'paid'], true);

        $status = match (true) {
            $hasIssue => 'requires_amendment',
            $allMatched => $protected ? $invoice->status : 'evaluated',
            default => $this->isInvoiceComplete($invoice) ? 'recorded' : 'uploaded',
        };

        $issueNotes = $hasIssue
            ? ($entries->firstWhere('status', 'issue')?->issue_notes ?? 'Invoice does not match itinerary.')
            : null;

        $invoice->update([
            'status' => $status,
            'issue_notes' => $issueNotes,
            'evaluated_by' => $entries->isNotEmpty() ? $userId : null,
            'evaluated_at' => $entries->isNotEmpty() ? now() : null,
        ]);
    }

    private function isInvoiceComplete(SupplierInvoice $invoice): bool
    {
        return filled($invoice->invoice_date)
            && filled($invoice->invoice_number)
            && $invoice->amount > 0;
    }
}
