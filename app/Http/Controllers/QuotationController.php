<?php

namespace App\Http\Controllers;

use App\Services\AvailabilityService;
use App\Services\MailService;
use App\Services\QuotationPricingService;
use App\Services\ProposalWorkflowService;
use App\Services\ReservationMailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class QuotationController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.quotations.index', [
            'quotations' => DB::table('quotations')
                ->join('clients', 'clients.id', '=', 'quotations.client_id')
                ->select('quotations.*', 'clients.name as client_name')
                ->when($request->filled('status'), fn ($query) => $query->where('quotations.status', $request->status))
                ->when($request->filled('search'), fn ($query) => $query->where(function ($query) use ($request) {
                    $query->where('quotations.reference', 'like', '%'.$request->search.'%')
                        ->orWhere('quotations.title', 'like', '%'.$request->search.'%')
                        ->orWhere('clients.name', 'like', '%'.$request->search.'%');
                }))
                ->latest('quotations.updated_at')->paginate(20)->withQueryString(),
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.quotations.create', [
            'clients' => DB::table('clients')->orderBy('name')->get(),
            'selectedClient' => $request->integer('client'),
            'enquiry' => $request->filled('enquiry') ? DB::table('website_enquiries')->find($request->integer('enquiry')) : null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'title' => ['required', 'string', 'max:180'],
            'start_date' => ['required', 'date'],
            'duration_days' => ['required', 'integer', 'min:1', 'max:60'],
            'guest_count' => ['required', 'integer', 'min:1', 'max:100'],
            'start_location' => ['required', 'string', 'max:120'],
            'currency' => ['required', 'string', 'size:3'],
            'office_markup_percent' => ['required', 'numeric', 'min:0', 'max:500'],
            'misc_markup_percent' => ['required', 'numeric', 'min:0', 'max:500'],
        ]);

        $quotationId = DB::transaction(function () use ($data) {
            $id = DB::table('quotations')->insertGetId([
                ...$data, 'reference' => 'QT-'.now()->format('Y').'-'.str_pad((string) (DB::table('quotations')->count() + 1), 4, '0', STR_PAD_LEFT),
                'exchange_rate' => 1, 'status' => 'draft', 'frozen' => false,
                'created_at' => now(), 'updated_at' => now(),
            ]);
            for ($day = 1; $day <= $data['duration_days']; $day++) {
                DB::table('quotation_days')->insert([
                    'quotation_id' => $id, 'day_number' => $day,
                    'travel_date' => Carbon::parse($data['start_date'])->addDays($day - 1)->toDateString(),
                    'from_location' => $day === 1 ? $data['start_location'] : null,
                    'to_location' => null, 'description' => null,
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }

            return $id;
        });

        return redirect()->route('admin.quotations.show', $quotationId)->with('success', 'Quotation created. Build the day-by-day program and reserve each service.');
    }

    public function show(Request $request, int $quotation, ProposalWorkflowService $workflowService, ReservationMailService $reservationMailService): View
    {
        $workflowService->synchronize();
        $record = DB::table('quotations')
            ->join('clients', 'clients.id', '=', 'quotations.client_id')
            ->select('quotations.*', 'clients.name as client_name', 'clients.email as client_email', 'clients.phone as client_phone')
            ->where('quotations.id', $quotation)->first();
        abort_unless($record, 404);

        $days = DB::table('quotation_days')->where('quotation_id', $quotation)->orderBy('day_number')->get();
        foreach ($days as $day) {
            $day->items = DB::table('quotation_items')->where('quotation_day_id', $day->id)->orderBy('id')->get();
            $day->route = $day->from_location && $day->to_location
                ? DB::table('route_distances')->where(function ($query) use ($day) {
                    $query->where('from_location', $day->from_location)->where('to_location', $day->to_location);
                })->orWhere(function ($query) use ($day) {
                    $query->where('from_location', $day->to_location)->where('to_location', $day->from_location);
                })->first()
                : null;
        }

        $paid = (float) DB::table('quotation_payments')->where('quotation_id', $quotation)->sum('amount');
        $reservationPaid = (float) DB::table('reservations')->where('quotation_id', $quotation)->sum('paid_amount');
        $extraExpenses = (float) DB::table('trip_expenses')->where('quotation_id', $quotation)->sum('amount');
        $actualCost = $reservationPaid + $extraExpenses;
        $revenue = max($paid, (float) $record->sell_total);

        $workflow = DB::table('proposal_workflows')->where('quotation_id', $quotation)->first();
        if ($workflow && !$workflow->customer_message) {
            $message = DB::table('website_enquiries')->where('email',$record->client_email)->latest('created_at')->value('message')
                ?: DB::table('leads')->where('email',$record->client_email)->latest('created_at')->value('notes');
            if ($message) {
                DB::table('proposal_workflows')->where('quotation_id',$quotation)->update(['customer_message'=>$message,'updated_at'=>now()]);
                $workflow = DB::table('proposal_workflows')->where('quotation_id',$quotation)->first();
            }
        }

        $reservationMailService->ensureForQuotation($quotation);
        $reservations = $reservationMailService->recordsForQuotation($quotation);
        foreach ($reservations as $reservation) {
            $reservation->email_history = DB::table('reservation_emails')->where('reservation_id',$reservation->id)->latest('created_at')->get();
        }
        $snapshots = DB::table('proposal_snapshots')->leftJoin('users','users.id','=','proposal_snapshots.created_by')
            ->where('proposal_snapshots.quotation_id',$quotation)->select('proposal_snapshots.*','users.name as creator_name')->latest('proposal_snapshots.created_at')->get();
        $snapshotChanges = [];
        if (count($request->input('snapshots', [])) === 2) {
            $selected = DB::table('proposal_snapshots')->where('quotation_id',$quotation)->whereIn('id',$request->input('snapshots'))->orderBy('created_at')->get();
            if ($selected->count() === 2) $snapshotChanges = ProposalWorkspaceController::compareSnapshots($selected[0],$selected[1]);
        }
        $activeTab = in_array($request->input('tab'), ['settings','persons','program','supplements','surcharges','discounts','overview','pdfs','snapshots','reservations','evaluation','predeparture','movements','deadlines'], true) ? $request->input('tab') : 'settings';

        return view('admin.quotations.show', [
            'quotation' => $record,
            'days' => $days,
            'rooms' => DB::table('room_types')->join('hotels', 'hotels.id', '=', 'room_types.hotel_id')->select('room_types.*', 'hotels.name as hotel_name', 'hotels.default_markup_percent')->where('room_types.active', true)->orderBy('hotels.name')->get(),
            'activities' => DB::table('tour_activities')->where('status', 'active')->orderBy('name')->get(),
            'vehicles' => DB::table('vehicles')->where('status', '!=', 'maintenance')->orderBy('number_plate')->get(),
            'locations' => DB::table('route_distances')->pluck('from_location')->merge(DB::table('route_distances')->pluck('to_location'))->unique()->sort()->values(),
            'reservations' => $reservations,
            'payments' => DB::table('quotation_payments')->where('quotation_id', $quotation)->latest('paid_at')->get(),
            'expenses' => DB::table('trip_expenses')->where('quotation_id', $quotation)->latest('expense_date')->get(),
            'financials' => [
                'client_paid' => $paid,
                'balance' => max(0, (float) $record->sell_total - $paid),
                'supplier_paid' => $reservationPaid,
                'extra_expenses' => $extraExpenses,
                'actual_cost' => $actualCost,
                'actual_profit' => $paid - $actualCost,
                'projected_profit' => (float) $record->sell_total - (float) $record->buy_total,
                'revenue_basis' => $revenue,
            ],
            'clientLink' => $workflow?->client_token ? route('proposal.client', $workflow->client_token) : null,
            'workflow' => $workflow,
            'sellerName' => $workflow?->seller_id ? (DB::table('users')->where('id',$workflow->seller_id)->value('name') ?: auth()->user()->name) : auth()->user()->name,
            'activeTab' => $activeTab,
            'travelers' => DB::table('proposal_travelers')->where('quotation_id',$quotation)->orderBy('id')->get(),
            'adjustments' => DB::table('proposal_adjustments')->where('quotation_id',$quotation)->orderBy('id')->get(),
            'documents' => DB::table('proposal_documents')->leftJoin('users','users.id','=','proposal_documents.uploaded_by')->where('proposal_documents.quotation_id',$quotation)->select('proposal_documents.*','users.name as uploader_name')->latest('proposal_documents.created_at')->get(),
            'snapshots' => $snapshots,
            'snapshotChanges' => $snapshotChanges,
            'reservationMailSummary' => [
                'total'=>$reservations->count(),
                'sent'=>$reservations->whereNotNull('reservation_mail_sent_at')->count(),
                'ready'=>$reservations->whereNull('reservation_mail_sent_at')->filter(fn($r)=>!empty($r->supplier_email))->count(),
                'missing'=>$reservations->whereNull('reservation_mail_sent_at')->filter(fn($r)=>empty($r->supplier_email))->count(),
            ],
        ]);
    }

    public function update(Request $request, int $quotation): RedirectResponse
    {
        $before = DB::table('quotations')->find($quotation);
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'start_date' => ['required', 'date'],
            'guest_count' => ['required', 'integer', 'min:1', 'max:100'],
            'start_location' => ['required', 'string', 'max:120'],
            'currency' => ['required', 'string', 'size:3'],
            'office_markup_percent' => ['required', 'numeric', 'min:0', 'max:500'],
            'misc_markup_percent' => ['required', 'numeric', 'min:0', 'max:500'],
            'status' => ['required', 'in:draft,active,sent,accepted,confirmed,in_progress,completed,cancelled'],
        ]);
        DB::table('quotations')->where('id', $quotation)->update([
            ...$data, 'frozen' => $request->boolean('frozen'), 'updated_at' => now(),
        ]);
        if ($before && $before->status !== $data['status']) {
            ProposalWorkspaceController::capture($quotation,(int)$request->user()->id,'Automatic · status '.$before->status.' → '.$data['status']);
        }

        return back()->with('success', 'Quotation settings updated.');
    }

    public function destroy(int $quotation): RedirectResponse
    {
        DB::table('quotations')->where('id', $quotation)->delete();

        return redirect()->route('admin.quotations.index')->with('success', 'Quotation deleted.');
    }

    public function updateDay(Request $request, int $quotation, int $day, AvailabilityService $availability): RedirectResponse
    {
        $data = $request->validate([
            'from_location' => ['nullable', 'string', 'max:120'],
            'to_location' => ['nullable', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);
        if ($warning = $availability->routeWarning($data['from_location'], $data['to_location'])) {
            return back()->withErrors(['route' => $warning])->withInput();
        }
        DB::table('quotation_days')->where('quotation_id', $quotation)->where('id', $day)->update([
            ...$data, 'updated_at' => now(),
        ]);

        return back()->with('success', 'Day route and program updated.');
    }

    public function storeItem(Request $request, int $quotation, int $day, QuotationPricingService $pricing): RedirectResponse
    {
        $data = $request->validate([
            'item_type' => ['required', 'in:room,activity,vehicle,fee,other'],
            'source_id' => ['nullable', 'integer'],
            'title' => ['nullable', 'string', 'max:180'],
            'source' => ['nullable', 'string', 'max:180'],
            'quantity' => ['required', 'numeric', 'min:0.01', 'max:1000'],
            'buy_unit_price' => ['nullable', 'numeric', 'min:0'],
            'markup_percent' => ['nullable', 'numeric', 'min:0', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1500'],
        ]);
        $dayRecord = DB::table('quotation_days')->where('quotation_id', $quotation)->where('id', $day)->first();
        abort_unless($dayRecord, 404);

        $resolved = $this->resolveItem($data, $dayRecord->travel_date);
        $markup = (float) ($data['markup_percent'] ?? $resolved['markup']);
        $buy = (float) ($data['buy_unit_price'] ?? $resolved['buy']);
        $sell = $pricing->sellingPrice($buy, $markup);
        $quantity = (float) $data['quantity'];

        DB::table('quotation_items')->insert([
            'quotation_day_id' => $day, 'item_type' => $data['item_type'],
            'source_id' => ($data['source_id'] ?? null) ?: null,
            'title' => ($data['title'] ?? null) ?: $resolved['title'],
            'source' => ($data['source'] ?? null) ?: $resolved['source'],
            'calculation_type' => $resolved['calculation'],
            'quantity' => $quantity, 'buy_unit_price' => $buy, 'markup_percent' => $markup,
            'sell_unit_price' => $sell, 'buy_total' => $buy * $quantity,
            'sell_total' => $sell * $quantity, 'currency' => $resolved['currency'],
            'notes' => $data['notes'] ?? null, 'created_at' => now(), 'updated_at' => now(),
        ]);
        $pricing->recalculate($quotation);

        return back()->with('success', 'Cost item added and quotation margin recalculated.');
    }

    public function destroyItem(int $quotation, int $day, int $item, QuotationPricingService $pricing): RedirectResponse
    {
        DB::table('quotation_items')->where('quotation_day_id', $day)->where('id', $item)->delete();
        $pricing->recalculate($quotation);

        return back()->with('success', 'Quotation item removed.');
    }

    public function storePayment(Request $request, int $quotation): RedirectResponse
    {
        $data = $request->validate([
            'reference' => ['required', 'string', 'max:100'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'string', 'size:3'],
            'paid_at' => ['required', 'date'],
            'method' => ['required', 'string', 'max:80'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);
        DB::table('quotation_payments')->insert([...$data, 'quotation_id' => $quotation, 'created_at' => now(), 'updated_at' => now()]);

        return back()->with('success', 'Client payment recorded.');
    }

    public function destroyPayment(int $quotation, int $payment): RedirectResponse
    {
        DB::table('quotation_payments')->where('quotation_id', $quotation)->where('id', $payment)->delete();

        return back()->with('success', 'Payment removed.');
    }

    public function storeExpense(Request $request, int $quotation): RedirectResponse
    {
        $data = $request->validate([
            'category' => ['required', 'string', 'max:100'],
            'supplier' => ['nullable', 'string', 'max:180'],
            'description' => ['required', 'string', 'max:500'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'string', 'size:3'],
            'expense_date' => ['required', 'date'],
            'payment_reference' => ['nullable', 'string', 'max:100'],
        ]);
        DB::table('trip_expenses')->insert([
            ...$data, 'quotation_id' => $quotation,
            'was_quoted' => $request->boolean('was_quoted'),
            'charged_to_client' => $request->boolean('charged_to_client'),
            'created_at' => now(), 'updated_at' => now(),
        ]);

        return back()->with('success', 'Trip expense recorded and included in actual profit/loss.');
    }

    public function destroyExpense(int $quotation, int $expense): RedirectResponse
    {
        DB::table('trip_expenses')->where('quotation_id', $quotation)->where('id', $expense)->delete();

        return back()->with('success', 'Expense removed.');
    }

    private function resolveItem(array $data, string $date): array
    {
        if ($data['item_type'] === 'room' && ($data['source_id'] ?? null)) {
            $room = DB::table('room_types')->join('hotels', 'hotels.id', '=', 'room_types.hotel_id')
                ->select('room_types.*', 'hotels.name as hotel_name', 'hotels.default_markup_percent', 'hotels.currency')
                ->where('room_types.id', $data['source_id'])->first();
            abort_unless($room, 422);
            $rate = DB::table('hotel_rates')->where('room_type_id', $room->id)
                ->whereDate('valid_from', '<=', $date)->whereDate('valid_to', '>=', $date)->first()
                ?? DB::table('hotel_rates')->where('room_type_id', $room->id)->latest('valid_to')->first();

            return ['title' => "{$room->hotel_name}: {$room->name}", 'source' => $room->hotel_name, 'calculation' => 'per_room', 'buy' => $rate?->buy_rate ?? 0, 'markup' => $rate?->markup_percent ?? $room->default_markup_percent, 'currency' => $rate?->currency ?? $room->currency];
        }
        if ($data['item_type'] === 'activity' && ($data['source_id'] ?? null)) {
            $activity = DB::table('tour_activities')->find($data['source_id']);
            abort_unless($activity, 422);

            return ['title' => $activity->name, 'source' => $activity->supplier, 'calculation' => $activity->calculation_type, 'buy' => $activity->buy_rate, 'markup' => $activity->markup_percent, 'currency' => $activity->currency];
        }
        if ($data['item_type'] === 'vehicle' && ($data['source_id'] ?? null)) {
            $vehicle = DB::table('vehicles')->find($data['source_id']);
            abort_unless($vehicle, 422);

            return ['title' => "{$vehicle->name} ({$vehicle->number_plate})", 'source' => 'SafariFlow Fleet', 'calculation' => 'per_vehicle', 'buy' => $vehicle->daily_buy_rate, 'markup' => $vehicle->markup_percent, 'currency' => $vehicle->currency];
        }

        return ['title' => ($data['title'] ?? null) ?: Str::headline($data['item_type']), 'source' => $data['source'] ?? null, 'calculation' => 'per_item', 'buy' => $data['buy_unit_price'] ?? 0, 'markup' => $data['markup_percent'] ?? 20, 'currency' => 'USD'];
    }

    public function downloadPdf(int $quotation)
    {
        $record = DB::table('quotations')
            ->join('clients', 'clients.id', '=', 'quotations.client_id')
            ->select('quotations.*', 'clients.name as client_name', 'clients.email as client_email', 'clients.phone as client_phone')
            ->where('quotations.id', $quotation)->first();
        abort_unless($record, 404);

        $days = DB::table('quotation_days')->where('quotation_id', $quotation)->orderBy('day_number')->get();
        foreach ($days as $day) {
            $day->items = DB::table('quotation_items')->where('quotation_day_id', $day->id)->orderBy('id')->get();
        }

        $adjustments = DB::table('proposal_adjustments')->where('quotation_id', $quotation)->orderBy('id')->get();
        $travelers = DB::table('proposal_travelers')->where('quotation_id', $quotation)->orderBy('id')->get();

        $supplementTotal = $adjustments->where('type','supplement')->sum(fn($a)=>(float)$a->unit_amount*(float)$a->quantity);
        $surchargeTotal = $adjustments->where('type','surcharge')->sum(fn($a)=>(float)$a->unit_amount*(float)$a->quantity);
        $discountTotal = $adjustments->where('type','discount')->sum(fn($a)=>(float)$a->unit_amount*(float)$a->quantity);
        $grandTotal = (float)$record->sell_total + $supplementTotal + $surchargeTotal - $discountTotal;

        $html = view('pdf.quotation', [
            'quotation' => $record,
            'days' => $days,
            'adjustments' => $adjustments,
            'travelers' => $travelers,
            'supplementTotal' => $supplementTotal,
            'surchargeTotal' => $surchargeTotal,
            'discountTotal' => $discountTotal,
            'grandTotal' => $grandTotal,
        ])->render();

        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => false,
        ]);

        $filename = \Illuminate\Support\Str::slug($record->reference . '-' . $record->title) . '.pdf';
        return $pdf->download($filename);
    }

    public function sendReadyToBook(Request $request, int $quotation, MailService $mail): RedirectResponse
    {
        return $this->sendLifecycleEmail($request, $quotation, $mail, 'ready_to_book');
    }

    public function sendPreConfirmation(Request $request, int $quotation, MailService $mail): RedirectResponse
    {
        return $this->sendLifecycleEmail($request, $quotation, $mail, 'pre_confirmation');
    }

    public function sendConfirmation(Request $request, int $quotation, MailService $mail): RedirectResponse
    {
        $response = $this->sendLifecycleEmail($request, $quotation, $mail, 'confirmation');
        if (str_contains($response->getSession()->get('success', '') ?? '', 'sent')) {
            DB::table('quotations')->where('id', $quotation)->update([
                'status' => 'confirmed',
                'updated_at' => now(),
            ]);
        }
        return $response;
    }

    private function sendLifecycleEmail(Request $request, int $quotation, MailService $mail, string $category): RedirectResponse
    {
        $quotation = DB::table('quotations')->where('id', $quotation)->first();
        if (! $quotation) {
            return back()->withErrors(['quotation' => 'Quotation not found.']);
        }

        $client = DB::table('clients')->where('id', $quotation->client_id)->first();

        $recipients = $mail->recipientsForQuotation($quotation->id);
        if (! $recipients) {
            return back()->withErrors(['quotation' => 'No client email on this quotation. Add a client email first.']);
        }

        $context = [
            'client_name' => $client->name ?? 'Traveller',
            'quotation_reference' => $quotation->reference ?: ('Q-'.$quotation->id),
            'currency' => $quotation->currency ?: 'USD',
            'amount' => number_format((float)($quotation->sell_total ?? 0), 2),
            'start_date' => $quotation->start_date ? Carbon::parse($quotation->start_date)->toFormattedDateString() : 'To be confirmed',
            'company' => 'Shishi Footsteps',
        ];

        $base = $mail->templateFor($category, $context);
        $subject = $request->input('subject') ?: $base['subject'];
        $body = $request->input('body') ?: $base['body'];

        $errors = [];
        $sent = 0;
        foreach ($recipients as $r) {
            $result = $mail->send($category, $r['email'], $r['name'] ?? null, $subject, $body, 'quotation', $quotation->id, $request->user()?->id);
            if ($result['success']) {
                $sent++;
            } else {
                $errors[] = ($r['email'] ?? '').': '.($result['error'] ?? 'failed');
            }
        }

        if ($sent) {
            try {
                DB::table('audit_logs')->insert([
                    'user_id' => $request->user()?->id,
                    'action' => 'email_'.$category,
                    'module' => 'quotations',
                    'description' => 'Quotation #'.$quotation->id.': '.$sent.' email(s) sent via '.$category,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Throwable $e) {
                // Audit log is optional – don't break the user flow.
            }
            return back()->with('success', ucfirst(str_replace('_', ' ', $category)).' email sent to '.($sent === 1 ? 'client' : $sent.' recipients'));
        }

        return back()->withErrors(['mail' => implode("\n", $errors)]);
    }
}
