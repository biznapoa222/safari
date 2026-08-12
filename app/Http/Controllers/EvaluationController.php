<?php

namespace App\Http\Controllers;

use App\Models\EvaluationEntry;
use App\Models\ProposalEvaluation;
use App\Models\SupplierInvoice;
use App\Models\User;
use App\Notifications\EvaluationNotification;
use App\Notifications\SupplierInvoiceWorkflowNotification;
use App\Services\EvaluationService;
use App\Services\KpiDashboardService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class EvaluationController extends Controller
{
    public function __construct(
        protected EvaluationService $evaluationService,
        protected KpiDashboardService $kpiService,
    ) {}

    public function overview(Request $request): View
    {
        $kpiData = $this->kpiService->getEvaluationKpis();
        $officerKpis = $this->kpiService->getReservationOfficerKpis();
        $weeklyTrend = $this->kpiService->getWeeklyTrend();
        $monthlyTrend = $this->kpiService->getMonthlyTrend();
        $statusDistribution = $this->kpiService->getStatusDistribution();
        $invoiceStatus = $this->kpiService->getInvoiceStatusDistribution();
        $proposalAging = $this->kpiService->getProposalAging();

        $recentEntries = EvaluationEntry::whereNotNull('evaluated_at')
            ->latest('evaluated_at')
            ->take(20)
            ->get();

        return view('admin.evaluations.dashboard', compact(
            'kpiData', 'officerKpis', 'weeklyTrend', 'monthlyTrend',
            'statusDistribution', 'invoiceStatus', 'proposalAging', 'recentEntries',
        ));
    }

    public function index(Request $request): View
    {
        $evaluations = DB::table('quotations')
            ->join('clients', 'clients.id', '=', 'quotations.client_id')
            ->leftJoin('proposal_evaluations', 'proposal_evaluations.quotation_id', '=', 'quotations.id')
            ->select(
                'quotations.*',
                'clients.name as client_name',
                DB::raw("COALESCE(proposal_evaluations.status, 'pending') as evaluation_status"),
            )
            ->whereIn('quotations.status', ['confirmed', 'in_progress', 'completed'])
            ->when($request->filled('status'), fn ($query) => $query->where(DB::raw("COALESCE(proposal_evaluations.status, 'pending')"), $request->status))
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($inner) use ($request) {
                    $inner->where('quotations.reference', 'like', '%'.$request->search.'%')
                        ->orWhere('quotations.title', 'like', '%'.$request->search.'%')
                        ->orWhere('clients.name', 'like', '%'.$request->search.'%');
                });
            })
            ->latest('quotations.updated_at')
            ->paginate(20)
            ->withQueryString();

        foreach ($evaluations as $evaluation) {
            $evaluation->entry_count = DB::table('quotation_items')
                ->join('quotation_days', 'quotation_days.id', '=', 'quotation_items.quotation_day_id')
                ->where('quotation_days.quotation_id', $evaluation->id)
                ->count();
            $evaluation->matched_count = DB::table('evaluation_entries')
                ->where('quotation_id', $evaluation->id)
                ->where('status', 'matched')
                ->count();
            $evaluation->invoice_count = DB::table('supplier_invoices')
                ->where('quotation_id', $evaluation->id)
                ->count();
        }

        return view('admin.evaluations.index', compact('evaluations'));
    }

    public function invoices(Request $request): View
    {
        $invoices = DB::table('supplier_invoices')
            ->join('quotations', 'quotations.id', '=', 'supplier_invoices.quotation_id')
            ->join('clients', 'clients.id', '=', 'quotations.client_id')
            ->leftJoin('users', 'users.id', '=', 'supplier_invoices.uploaded_by')
            ->select('supplier_invoices.*', 'quotations.reference as quotation_reference', 'clients.name as client_name', 'users.name as uploader_name')
            ->when($request->filled('status'), fn ($query) => $query->where('supplier_invoices.status', $request->status))
            ->latest('supplier_invoices.created_at')
            ->paginate(25)
            ->withQueryString();

        return view('admin.evaluations.invoices', [
            'invoices' => $invoices,
            'quotations' => DB::table('quotations')
                ->whereIn('status', ['confirmed', 'in_progress', 'completed'])
                ->orderByDesc('updated_at')
                ->get(),
            'reservations' => DB::table('reservations')
                ->join('quotations', 'quotations.id', '=', 'reservations.quotation_id')
                ->select('reservations.*', 'quotations.reference as quotation_reference')
                ->latest('reservations.created_at')
                ->get(),
        ]);
    }

    public function show(int $quotation): View
    {
        $this->evaluationService->syncEntries($quotation);

        $record = DB::table('quotations')
            ->join('clients', 'clients.id', '=', 'quotations.client_id')
            ->select('quotations.*', 'clients.name as client_name', 'clients.email as client_email')
            ->where('quotations.id', $quotation)
            ->first();
        abort_unless($record, 404);

        $entries = DB::table('evaluation_entries')
            ->leftJoin('quotation_days', 'quotation_days.id', '=', 'evaluation_entries.quotation_day_id')
            ->leftJoin('supplier_invoices', 'supplier_invoices.id', '=', 'evaluation_entries.supplier_invoice_id')
            ->select(
                'evaluation_entries.*',
                'quotation_days.day_number',
                'quotation_days.travel_date',
                'supplier_invoices.invoice_number',
                'supplier_invoices.company_name as invoice_company',
                'supplier_invoices.amount as invoice_amount',
                'supplier_invoices.currency as invoice_currency',
                'supplier_invoices.status as invoice_status',
            )
            ->where('evaluation_entries.quotation_id', $quotation)
            ->orderBy('quotation_days.day_number')
            ->orderBy('evaluation_entries.id')
            ->get();

        $invoices = DB::table('supplier_invoices')
            ->leftJoin('users', 'users.id', '=', 'supplier_invoices.uploaded_by')
            ->select('supplier_invoices.*', 'users.name as uploader_name')
            ->where('supplier_invoices.quotation_id', $quotation)
            ->latest('supplier_invoices.created_at')
            ->get();

        $evaluation = DB::table('proposal_evaluations')->where('quotation_id', $quotation)->first();
        $missing = $this->evaluationService->findMissingInvoices($quotation);
        $auditLogs = DB::table('evaluation_audit_logs')
            ->where('quotation_id', $quotation)
            ->leftJoin('users', 'users.id', '=', 'evaluation_audit_logs.user_id')
            ->select('evaluation_audit_logs.*', 'users.name as user_name')
            ->latest('evaluation_audit_logs.created_at')
            ->take(20)
            ->get();

        $supplierCount = DB::table('evaluation_entries')
            ->where('quotation_id', $quotation)
            ->whereNotNull('supplier')
            ->distinct('supplier')
            ->count('supplier');

        $allEntries = EvaluationEntry::where('quotation_id', $quotation)->get();
        $summary = [
            'total' => $allEntries->count(),
            'matched' => $allEntries->where('status', 'matched')->count(),
            'issues' => $allEntries->where('status', 'issue')->count(),
            'missing' => $allEntries->where('status', 'missing_invoice')->count(),
            'variance' => $allEntries->sum('discrepancy'),
            'supplier_count' => $supplierCount,
            'invoice_count' => $invoices->count(),
            'pending_invoices' => $invoices->whereIn('status', ['uploaded', 'recorded'])->count(),
            'matched_invoices' => $invoices->where('status', 'approved')->count(),
        ];

        return view('admin.evaluations.show', compact(
            'quotation', 'entries', 'invoices', 'evaluation', 'summary',
            'missing', 'auditLogs',
        ));
    }

    public function uploadDocument(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'quotation_id' => ['required', 'exists:quotations,id'],
            'reservation_id' => ['nullable', 'exists:reservations,id'],
            'company_name' => ['required', 'string', 'max:180'],
            'comments' => ['nullable', 'string', 'max:2000'],
            'document' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
        ]);
        $this->validateReservationQuotation($data['reservation_id'] ?? null, (int) $data['quotation_id']);

        $path = $request->file('document')->store('supplier-invoices/'.now()->format('Y'), 'local');
        DB::table('supplier_invoices')->insert([
            'quotation_id' => $data['quotation_id'],
            'reservation_id' => $data['reservation_id'] ?? null,
            'uploaded_by' => $request->user()->id,
            'company_name' => $data['company_name'],
            'comments' => $data['comments'] ?? null,
            'file_path' => $path,
            'file_name' => $request->file('document')->getClientOriginalName(),
            'status' => 'uploaded',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Supplier invoice uploaded and sent to the evaluation queue.');
    }

    public function storeInvoice(Request $request, int $quotation): RedirectResponse
    {
        $data = $this->validateInvoice($request, $quotation);
        $path = $request->hasFile('document')
            ? $request->file('document')->store('supplier-invoices/'.now()->format('Y'), 'local')
            : null;

        $id = DB::table('supplier_invoices')->insertGetId([
            ...$data,
            'quotation_id' => $quotation,
            'uploaded_by' => $request->user()->id,
            'file_path' => $path,
            'file_name' => $request->hasFile('document') ? $request->file('document')->getClientOriginalName() : null,
            'status' => 'recorded',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->evaluationService->logAudit(
            'create_invoice', "Invoice {$data['invoice_number']} created for {$data['company_name']}",
            $quotation, (int) $id, null, (int) $request->user()->id,
        );

        return back()->with('success', 'Invoice details added to the confirmed proposal.');
    }

    public function updateInvoice(Request $request, int $invoice): RedirectResponse
    {
        $record = DB::table('supplier_invoices')->find($invoice);
        abort_unless($record, 404);
        $data = $this->validateInvoice($request, (int) $record->quotation_id);
        $path = $request->hasFile('document')
            ? $request->file('document')->store('supplier-invoices/'.now()->format('Y'), 'local')
            : $record->file_path;

        if ($request->hasFile('document') && $record->file_path) {
            if (Storage::disk('local')->exists($record->file_path)) Storage::disk('local')->delete($record->file_path);
            elseif (Storage::disk('public')->exists($record->file_path)) Storage::disk('public')->delete($record->file_path);
        }

        DB::table('supplier_invoices')->where('id', $invoice)->update([
            ...$data,
            'file_path' => $path,
            'file_name' => $request->hasFile('document') ? $request->file('document')->getClientOriginalName() : $record->file_name,
            'status' => $record->status === 'uploaded' ? 'recorded' : $record->status,
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Invoice information updated.');
    }

    public function downloadInvoice(int $invoice): StreamedResponse
    {
        $record = DB::table('supplier_invoices')->find($invoice);
        abort_unless($record && $record->file_path, 404);

        if (Storage::disk('local')->exists($record->file_path)) {
            return Storage::disk('local')->download($record->file_path, $record->file_name ?: basename($record->file_path));
        }

        abort_unless(Storage::disk('public')->exists($record->file_path), 404);

        return Storage::disk('public')->download($record->file_path, $record->file_name ?: basename($record->file_path));
    }

    public function updateEntry(Request $request, int $entry): RedirectResponse
    {
        $record = DB::table('evaluation_entries')->find($entry);
        abort_unless($record, 404);
        $data = $request->validate([
            'supplier_invoice_id' => ['nullable', 'exists:supplier_invoices,id'],
            'invoice_rate' => ['nullable', 'numeric', 'min:0'],
            'meal_plan' => ['nullable', 'string', 'max:120'],
            'room_configuration' => ['nullable', 'string', 'max:120'],
            'room_type' => ['nullable', 'string', 'max:120'],
            'issue_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        if (! empty($data['supplier_invoice_id'])) {
            $invoice = DB::table('supplier_invoices')->find($data['supplier_invoice_id']);
            abort_if((int) $invoice->quotation_id !== (int) $record->quotation_id, 422, 'The selected invoice belongs to another proposal.');
            if (! $this->invoiceIsComplete($invoice)) {
                return back()->withErrors([
                    'supplier_invoice_id' => 'Complete the invoice date, number and amount before matching it to itinerary services.',
                ]);
            }
        }

        $isRoom = in_array($record->item_type, ['room', 'accommodation'], true);
        $checks = [
            'rate_matches' => $request->boolean('rate_matches'),
            'dates_match' => $request->boolean('dates_match'),
            'meal_plan_matches' => $isRoom ? $request->boolean('meal_plan_matches') : true,
            'room_configuration_matches' => $isRoom ? $request->boolean('room_configuration_matches') : true,
            'room_type_matches' => $isRoom ? $request->boolean('room_type_matches') : true,
        ];
        $status = empty($data['supplier_invoice_id'])
            ? 'missing_invoice'
            : (collect($checks)->every(fn (bool $matches) => $matches) ? 'matched' : 'issue');
        $invoiceRate = $data['invoice_rate'] ?? null;

        DB::transaction(function () use ($entry, $record, $data, $checks, $status, $invoiceRate, $request) {
            $entryModel = EvaluationEntry::find($entry);
            $oldStatus = $entryModel?->status;

            DB::table('evaluation_entries')->where('id', $entry)->update([
                ...$data,
                ...$checks,
                'invoice_rate' => $invoiceRate,
                'status' => $status,
                'evaluated_by' => $request->user()->id,
                'evaluated_at' => now(),
                'updated_at' => now(),
            ]);

            if ($entryModel) {
                $entryModel->refresh();
                $this->evaluationService->updateVariance($entryModel);
            }

            $invoiceIds = array_filter([
                $record->supplier_invoice_id,
                $data['supplier_invoice_id'] ?? null,
            ]);
            foreach (array_unique($invoiceIds) as $invoiceId) {
                $invoiceObj = SupplierInvoice::find($invoiceId);
                if ($invoiceObj) {
                    $entries = EvaluationEntry::where('supplier_invoice_id', $invoiceId)->get();
                    $hasIssue = $entries->contains(fn($e) => $e->status === 'issue');
                    $allMatched = $entries->isNotEmpty() && $entries->every(fn($e) => $e->status === 'matched');
                    $protected = in_array($invoiceObj->status, ['approved', 'payment_ready', 'paid'], true);
                    $newStatus = match (true) {
                        $hasIssue => 'requires_amendment',
                        $allMatched => $protected ? $invoiceObj->status : 'evaluated',
                        default => 'recorded',
                    };
                    $invoiceObj->update(['status' => $newStatus, 'updated_at' => now()]);
                }
            }

            $this->evaluationService->refreshEvaluationSummary((int) $record->quotation_id);
            $this->evaluationService->logAudit(
                "entry_{$status}", "Evaluation entry #{$entry} status: {$status}",
                (int) $record->quotation_id, (int) ($data['supplier_invoice_id'] ?? $record->supplier_invoice_id),
                $entry, (int) $request->user()->id,
                $oldStatus ? ['status' => $oldStatus] : null,
                ['status' => $status],
            );
        });

        if ($status === 'issue') {
            $this->evaluationService->notifyRoles(
                ['reservations', 'administrator'], 'variance_detected',
                $data['issue_notes'] ?? 'An invoice does not match the confirmed itinerary.',
                (int) $record->quotation_id, 'warning',
            );
        }

        return back()->with('success', $status === 'matched' ? 'Entry verified and matched.' : 'Entry saved for follow-up.');
    }

    public function updateInvoiceStatus(Request $request, int $invoice): RedirectResponse
    {
        $record = DB::table('supplier_invoices')->find($invoice);
        abort_unless($record, 404);
        $data = $request->validate([
            'action' => ['required', Rule::in(['send_to_finance', 'requires_amendment', 'mark_paid'])],
            'payment_deadline' => ['nullable', 'required_if:action,send_to_finance', 'date'],
            'issue_notes' => ['nullable', 'required_if:action,requires_amendment', 'string', 'max:2000'],
        ]);

        if ($data['action'] === 'send_to_finance' && $record->status !== 'approved') {
            return back()->withErrors(['invoice' => 'Only an approved invoice can be sent to finance.']);
        }
        if ($data['action'] === 'mark_paid' && $record->status !== 'payment_ready') {
            return back()->withErrors(['invoice' => 'Only an invoice already sent to finance can be marked as paid.']);
        }
        if ($data['action'] === 'requires_amendment' && $record->status === 'paid') {
            return back()->withErrors(['invoice' => 'A paid invoice cannot be returned for amendment.']);
        }

        $status = match ($data['action']) {
            'send_to_finance' => 'payment_ready',
            'mark_paid' => 'paid',
            default => 'requires_amendment',
        };
        DB::table('supplier_invoices')->where('id', $invoice)->update([
            'status' => $status,
            'payment_deadline' => $data['payment_deadline'] ?? $record->payment_deadline,
            'issue_notes' => $data['issue_notes'] ?? ($status === 'requires_amendment' ? $record->issue_notes : null),
            'updated_at' => now(),
        ]);

        $this->evaluationService->logAudit(
            "invoice_{$status}", "Invoice #{$record->invoice_number} status changed to {$status}",
            (int) $record->quotation_id, $invoice, null, (int) $request->user()->id,
        );
        if ($status === 'payment_ready') {
            $this->evaluationService->notifyRoles(['finance', 'administrator'], 'invoice_approved', $record->company_name.' invoice is approved for payment.', (int) $record->quotation_id, 'success', );
        } elseif ($status === 'requires_amendment') {
            $this->evaluationService->notifyRoles(['reservations', 'administrator'], 'invoice_amended', $data['issue_notes'], (int) $record->quotation_id, 'warning');
        }

        return back()->with('success', match ($status) {
            'payment_ready' => 'Invoice shared with finance for payment.',
            'paid' => 'Invoice marked as paid.',
            default => 'Reservations have been notified to amend the invoice.',
        });
    }

    public function approve(Request $request, int $quotation): RedirectResponse
    {
        $this->syncEntries($quotation);
        $entries = DB::table('evaluation_entries')->where('quotation_id', $quotation);
        $entryCount = (clone $entries)->count();
        $openEntries = (clone $entries)->where('status', '!=', 'matched')->count();

        if ($entryCount === 0) {
            return back()->withErrors(['evaluation' => 'Add itinerary services before approving this evaluation.']);
        }
        if ($openEntries > 0) {
            return back()->withErrors(['evaluation' => 'Resolve every missing invoice and mismatch before approving this evaluation.']);
        }

        DB::transaction(function () use ($quotation, $request) {
            DB::table('proposal_evaluations')->updateOrInsert(
                ['quotation_id' => $quotation],
                [
                    'status' => 'approved',
                    'approved_by' => $request->user()->id,
                    'approved_at' => now(),
                    'completed_at' => now(),
                    'updated_at' => now(),
                    'created_at' => now(),
                ],
            );
            DB::table('supplier_invoices')->where('quotation_id', $quotation)->where('status', 'evaluated')->update([
                'status' => 'approved',
                'updated_at' => now(),
            ]);
            $this->evaluationService->refreshEvaluationSummary($quotation);
        });

        $this->evaluationService->logAudit(
            'approve_evaluation', 'Evaluation approved for finance handoff',
            $quotation, null, null, (int) $request->user()->id,
        );
        $this->evaluationService->notifyRoles(
            ['finance', 'administrator'], 'evaluation_completed',
            "Evaluation #{$quotation} approved. Invoices ready for payment.", $quotation, 'success',
        );

        return back()->with('success', 'Evaluation approved. Supplier invoices can now be handed to finance.');
    }

    public function missingInvoices(int $quotation): View
    {
        $this->evaluationService->syncEntries($quotation);
        $missing = $this->evaluationService->findMissingInvoices($quotation);

        $record = DB::table('quotations')
            ->join('clients', 'clients.id', '=', 'quotations.client_id')
            ->select('quotations.*', 'clients.name as client_name')
            ->where('quotations.id', $quotation)
            ->first();
        abort_unless($record, 404);

        return view('admin.evaluations.missing', compact('missing', 'record'));
    }

    public function splitInvoice(Request $request, int $invoice): RedirectResponse
    {
        $data = $request->validate([
            'splits' => ['required', 'array', 'min:2'],
            'splits.*.amount' => ['required', 'numeric', 'min:0.01'],
            'splits.*.entry_id' => ['nullable', 'exists:evaluation_entries,id'],
        ]);

        try {
            $this->evaluationService->splitInvoice($invoice, $data['splits'], (int) $request->user()->id);
            $this->evaluationService->logAudit(
                'split_invoice', 'Invoice split into ' . count($data['splits']) . ' parts',
                null, $invoice, null, (int) $request->user()->id,
            );
            return back()->with('success', 'Invoice split successfully.');
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['split' => $e->getMessage()]);
        }
    }

    public function assignInvoice(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'entry_id' => ['required', 'exists:evaluation_entries,id'],
            'supplier_invoice_id' => ['required', 'exists:supplier_invoices,id'],
            'allocated_amount' => ['nullable', 'numeric', 'min:0'],
        ]);

        try {
            $this->evaluationService->assignInvoice(
                (int) $data['entry_id'],
                (int) $data['supplier_invoice_id'],
                isset($data['allocated_amount']) ? (float) $data['allocated_amount'] : null,
                (int) $request->user()->id,
            );
            $this->evaluationService->logAudit(
                'assign_invoice', 'Invoice assigned to itinerary entry',
                null, (int) $data['supplier_invoice_id'], (int) $data['entry_id'],
                (int) $request->user()->id,
            );
            return back()->with('success', 'Invoice assigned to itinerary entry.');
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['assign' => $e->getMessage()]);
        }
    }

    public function detectDuplicates(Request $request): View
    {
        $duplicates = DB::table('supplier_invoices as a')
            ->join('supplier_invoices as b', function ($join) {
                $join->on('a.quotation_id', '=', 'b.quotation_id')
                    ->on('a.invoice_number', '=', 'b.invoice_number')
                    ->on('a.id', '<', 'b.id');
            })
            ->select('a.*', 'b.id as duplicate_id', 'b.company_name as duplicate_company')
            ->when($request->filled('quotation_id'), fn($q) => $q->where('a.quotation_id', $request->quotation_id))
            ->get();

        return view('admin.evaluations.duplicates', ['duplicates' => $duplicates]);
    }

    public function auditLog(int $quotation): View
    {
        $logs = DB::table('evaluation_audit_logs')
            ->where('quotation_id', $quotation)
            ->leftJoin('users', 'users.id', '=', 'evaluation_audit_logs.user_id')
            ->select('evaluation_audit_logs.*', 'users.name as user_name')
            ->latest('evaluation_audit_logs.created_at')
            ->paginate(30);

        return view('admin.evaluations.audit', compact('logs', 'quotation'));
    }

    public function exportCsv(int $quotation): StreamedResponse
    {
        $entries = EvaluationEntry::where('quotation_id', $quotation)->get();

        return response()->streamDownload(function () use ($entries) {
            $header = ['Item Type', 'Title', 'Supplier', 'Service Date', 'System Rate', 'Invoice Rate', 'Discrepancy', 'Variance %', 'Status'];
            echo implode(',', $header) . "\n";
            foreach ($entries as $e) {
                echo implode(',', [
                    $e->item_type, '"' . str_replace('"', '""', $e->title) . '"',
                    '"' . str_replace('"', '""', $e->supplier ?? '') . '"',
                    $e->service_date, $e->system_rate, $e->invoice_rate ?? 0,
                    $e->discrepancy, $e->variance_percent, $e->status,
                ]) . "\n";
            }
        }, "evaluation-{$quotation}.csv", ['Content-Type' => 'text/csv']);
    }

    private function validateInvoice(Request $request, int $quotation): array
    {
        $data = $request->validate([
            'reservation_id' => ['nullable', 'exists:reservations,id'],
            'invoice_date' => ['required', 'date'],
            'invoice_number' => ['required', 'string', 'max:120'],
            'company_name' => ['required', 'string', 'max:180'],
            'amount' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'invoice_type' => ['required', Rule::in(['normal', 'supplement', 'credit_note'])],
            'invoice_category' => ['nullable', 'string', 'max:80'],
            'invoice_item_type' => ['nullable', 'string', 'max:80'],
            'vat_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'comments' => ['nullable', 'string', 'max:2000'],
            'payment_deadline' => ['nullable', 'date'],
            'document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
        ]);
        $this->validateReservationQuotation($data['reservation_id'] ?? null, $quotation);
        unset($data['document']);
        $data['vat_rate'] = $data['vat_rate'] ?? 0;
        $data['vat_reclaimable'] = $request->boolean('vat_reclaimable');
        $data['vat_amount'] = ($data['amount'] ?? 0) * ($data['vat_rate'] / 100);

        $errors = $this->evaluationService->validateInvoiceUpload($data, $quotation);
        if (!empty($errors)) {
            abort(422, implode('; ', $errors));
        }

        return $data;
    }

    private function validateReservationQuotation(?int $reservation, int $quotation): void
    {
        if ($reservation === null) return;
        abort_unless(
            DB::table('reservations')->where('id', $reservation)->where('quotation_id', $quotation)->exists(),
            422, 'The reservation does not belong to this proposal.',
        );
    }
}
