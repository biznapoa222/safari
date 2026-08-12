<?php

namespace App\Http\Controllers;

use App\Services\ProposalWorkflowService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProposalPlanningController extends Controller
{
    public function index(Request $request, ProposalWorkflowService $workflowService): View
    {
        $workflowService->synchronize();
        $records = $this->records($request);
        $page = max(1, $request->integer('page', 1));
        $perPage = 30;

        return view('admin.proposal-planning.index', [
            'records' => new LengthAwarePaginator($records->forPage($page, $perPage), $records->count(), $perPage, $page, [
                'path' => $request->url(), 'query' => $request->query(),
            ]),
            'stage' => $request->input('stage', 'confirmed'),
            'tripTab' => $request->input('trip', 'upcoming'),
            'planningStep' => $request->input('step', 'all'),
            'sellers' => DB::table('users')->where('is_active', true)->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function advance(int $quotation): RedirectResponse
    {
        $record = DB::table('quotations')->find($quotation);
        abort_unless($record, 404);
        $next = match ($record->status) {
            'draft' => 'active', 'active' => 'sent', 'sent' => 'accepted', 'accepted' => 'confirmed', default => $record->status,
        };
        DB::table('quotations')->where('id', $quotation)->update(['status' => $next, 'updated_at' => now()]);
        DB::table('proposal_workflows')->where('quotation_id', $quotation)->update([
            ...($next === 'sent' ? ['quotation_checked_at' => now()] : []),
            ...($next === 'accepted' ? ['leader_checked_at' => now()] : []),
            'updated_at' => now(),
        ]);
        if ($next !== $record->status) ProposalWorkspaceController::capture($quotation,(int)auth()->id(),'Automatic · stage '.$record->status.' → '.$next);
        return back()->with('success', $next === $record->status ? 'This proposal is already at its current automatic stage.' : 'Proposal advanced to '.str_replace('_', ' ', $next).'.');
    }

    public function toggle(Request $request, int $quotation): RedirectResponse
    {
        $data = $request->validate(['field' => ['required', Rule::in([
            'confirmation_sent_at', 'jeeps_planned_at', 'daily_movements_checked_at', 'pre_departure_checked_at',
        ])]]);
        $workflow = DB::table('proposal_workflows')->where('quotation_id', $quotation)->first();
        abort_unless($workflow, 404);
        DB::table('proposal_workflows')->where('quotation_id', $quotation)->update([
            $data['field'] => $workflow->{$data['field']} ? null : now(), 'updated_at' => now(),
        ]);
        return back()->with('success', 'Trip checklist updated.');
    }

    public function note(Request $request, int $quotation): RedirectResponse
    {
        $data = $request->validate([
            'planning_note' => ['nullable', 'string', 'max:1000'],
            'whatsapp_status' => ['nullable', 'string', 'max:120'],
        ]);
        DB::table('proposal_workflows')->where('quotation_id', $quotation)->update([...$data, 'updated_at' => now()]);
        return back()->with('success', 'Planning notes saved.');
    }

    public function export(Request $request, ProposalWorkflowService $workflowService): StreamedResponse
    {
        $workflowService->synchronize();
        $records = $this->records($request);
        return response()->streamDownload(function () use ($records) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Request', 'Proposal', 'Client', 'Seller', 'First day', 'Last day', 'Stage', 'Trip stage', 'Payment', 'Reservations']);
            foreach ($records as $record) {
                fputcsv($file, [$record->reference, $record->title, $record->client_name, $record->seller_name, $record->start_date, $record->end_date, $record->stage, $record->trip_stage, $record->payment_state, $record->reservation_state]);
            }
            fclose($file);
        }, 'proposal-planning-'.now()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv']);
    }

    private function records(Request $request): Collection
    {
        $today = Carbon::today();
        $records = DB::table('quotations')
            ->join('clients', 'clients.id', '=', 'quotations.client_id')
            ->join('proposal_workflows', 'proposal_workflows.quotation_id', '=', 'quotations.id')
            ->leftJoin('users', 'users.id', '=', 'proposal_workflows.seller_id')
            ->leftJoin('proposal_evaluations', 'proposal_evaluations.quotation_id', '=', 'quotations.id')
            ->select('quotations.*', 'clients.name as client_name', 'clients.country as client_country', 'proposal_workflows.*',
                'quotations.id as id', 'users.name as seller_name', 'proposal_evaluations.status as evaluation_status', 'proposal_evaluations.approved_at')
            ->orderBy('quotations.start_date')->get();

        foreach ($records as $record) {
            $start = Carbon::parse($record->start_date);
            $end = $start->copy()->addDays(max(0, (int) $record->duration_days - 1));
            $record->end_date = $end->toDateString();
            $record->stage = in_array($record->status, ['confirmed', 'in_progress', 'completed'], true) ? 'confirmed'
                : ($record->status === 'accepted' ? 'pre-confirmed' : 'planning');
            $record->planning_step = match ($record->status) {
                'draft' => 'in-planning', 'active' => 'quotation-check', 'sent' => 'team-leader-check', default => 'done',
            };
            $record->trip_stage = $record->evaluation_status === 'approved' ? 'evaluated'
                : ($today->gt($end) ? 'operated' : ($today->gte($start) ? 'in-operation' : 'upcoming'));
            $paid = (float) DB::table('quotation_payments')->where('quotation_id', $record->id)->sum('amount');
            $record->client_paid = $paid;
            $adjustments = DB::table('proposal_adjustments')->where('quotation_id',$record->id)->get();
            $adjustmentNet = $adjustments->whereIn('type',['supplement','surcharge'])->sum(fn($a)=>(float)$a->unit_amount*(float)$a->quantity)
                - $adjustments->where('type','discount')->sum(fn($a)=>(float)$a->unit_amount*(float)$a->quantity);
            $record->proposal_total = (float)$record->sell_total + $adjustmentNet;
            $record->payment_state = $record->proposal_total > 0 && $paid >= $record->proposal_total ? 'paid' : ($paid > 0 ? 'partial' : 'none');
            $reservations = DB::table('reservations')->where('quotation_id', $record->id)->get();
            $record->reservation_count = $reservations->count();
            $record->confirmed_reservations = $reservations->where('status', 'confirmed')->count();
            $record->reservation_state = $record->reservation_count === 0 ? 'New' : ($record->confirmed_reservations === $record->reservation_count ? 'Confirmed' : 'Checking availability');
            $record->reservation_person = $reservations->pluck('assigned_person')->filter()->first() ?: 'NO USER';
            $record->days_complete = (bool) $record->itinerary_completed_at;
            $record->seller_name = $record->seller_name ?: 'Safari Team';
        }

        $stage = $request->input('stage', 'confirmed');
        $trip = $request->input('trip', 'upcoming');
        return $records->filter(function ($record) use ($request, $stage, $trip) {
            $search = strtolower(trim((string) $request->input('search')));
            if ($record->stage !== $stage) return false;
            if ($stage === 'confirmed' && $record->trip_stage !== $trip) return false;
            if ($stage === 'planning' && $request->filled('step') && $request->step !== 'all' && $record->planning_step !== $request->step) return false;
            if ($request->filled('country') && strtolower($request->country) !== strtolower($record->country)) return false;
            if ($request->filled('seller') && (int) $request->seller !== (int) $record->seller_id) return false;
            if ($request->filled('type') && strtolower($request->type) !== strtolower($record->proposal_type)) return false;
            if ($search && ! str_contains(strtolower($record->reference.' '.$record->title.' '.$record->client_name), $search)) return false;
            return true;
        })->values();
    }
}
