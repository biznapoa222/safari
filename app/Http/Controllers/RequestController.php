<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequestRequest;
use App\Http\Requests\UpdateRequestRequest;
use App\Models\Request as RequestModel;
use App\Models\RequestTask;
use App\Repositories\RequestRepository;
use App\Services\QuotationPricingService;
use App\Services\RequestFilterService;
use App\Services\RequestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;

class RequestController extends Controller
{
    public function __construct(
        private RequestService $service,
        private RequestRepository $repository,
        private QuotationPricingService $pricingService,
        private RequestFilterService $filterService,
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only([
            'search', 'status', 'source', 'language', 'company', 'country', 'assigned_to', 'request_types',
            'followup_from', 'followup_to',
            'date_from', 'date_to', 'arrival_from', 'arrival_to',
            'destination', 'accommodation_tier', 'travel_type', 'priority',
            'rating', 'is_diamond', 'flag_color', 'sort', 'direction',
        ]);

        return view('admin.requests.index', [
            'stats' => $this->service->getStats(),
            'requests' => $this->service->list($filters, (int) $request->input('per_page', 20)),
            'filters' => $filters,
            'users' => \App\Models\User::query()->where('is_active', true)->orderBy('name')->get(['id', 'name']),
            'statuses' => array_keys(RequestModel::statusOptions()),
            'statusOptions' => RequestModel::statusOptions(),
            'countries' => $this->worldCountries(),
            'sources' => ['manual', 'website', 'whatsapp', 'email', 'walk_in', 'api', 'referral', 'phone'],
            'travelTypes' => ['safari', 'beach', 'city', 'adventure', 'cultural', 'honeymoon', 'family', 'business', 'group'],
            'accommodationTiers' => ['luxury', 'midrange', 'budget', 'camping'],
            'priorities' => ['low', 'medium', 'high', 'urgent'],
            'languages' => collect(config('safari.languages', []))->mapWithKeys(fn ($language, $code) => [$code => $language['name']])->all(),
        ]);
    }

    public function create(): View
    {
        return view('admin.requests.create', [
            'clients' => \App\Models\Client::query()->orderBy('name')->get(['id', 'name', 'email', 'phone', 'country']),
            'countries' => $this->worldCountries(),
            'users' => \App\Models\User::query()->where('is_active', true)->orderBy('name')->get(['id', 'name']),
            'statuses' => array_keys(RequestModel::statusOptions()),
            'sources' => ['manual', 'website', 'whatsapp', 'email', 'walk_in', 'api', 'referral', 'phone'],
            'travelTypes' => ['safari', 'beach', 'city', 'adventure', 'cultural', 'honeymoon', 'family', 'business', 'group'],
            'accommodationTiers' => ['luxury', 'midrange', 'budget', 'camping'],
            'priorities' => ['low', 'medium', 'high', 'urgent'],
        ]);
    }

    public function store(StoreRequestRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (!empty($data['client_id'])) {
            $client = \App\Models\Client::find($data['client_id']);
            if ($client) {
                $data['client_name'] = $data['client_name'] ?? $client->name;
                $data['client_email'] = $data['client_email'] ?? $client->email;
                $data['client_phone'] = $data['client_phone'] ?? $client->phone;
                $data['country'] = $data['country'] ?? $client->country;
            }
        } elseif (!empty($data['client_name']) && empty($data['client_id'])) {
            $client = $this->service->createClient([
                'name' => $data['client_name'],
                'email' => $data['client_email'] ?? null,
                'phone' => $data['client_phone'] ?? null,
                'country' => $data['country'] ?? null,
            ]);
            $data['client_id'] = $client->id;
        }

        $record = $this->service->create($data);

        return redirect()->route('admin.requests.show', $record->id)
            ->with('success', 'Request created successfully.');
    }

    public function show(int $id): View
    {
        $record = $this->service->find($id);
        abort_unless($record, 404);

        $proposals = $this->proposalsForRequest($record);

        return view('admin.requests.show', [
            'request' => $record,
            'notes' => $record->notes()->with('user')->latest('created_at')->get(),
            'tasks' => $record->tasks()->with('assignedUser')->latest()->get(),
            'files' => $record->files()->with('user')->latest('created_at')->get(),
            'history' => $record->history()->with('user')->latest('created_at')->get(),
            'followups' => $record->followups()->with('user')->latest()->get(),
            'statusLogs' => $record->statusLogs()->with('user')->latest('created_at')->get(),
            'flags' => $record->flags()->with('user')->latest('created_at')->get(),
            'users' => \App\Models\User::query()->where('is_active', true)->orderBy('name')->get(['id', 'name']),
            'statuses' => array_keys(RequestModel::statusOptions()),
            'workspaceStatuses' => RequestModel::workspaceStatusOptions(),
            'workspaceStatus' => $record->workspaceStatus(),
            'proposals' => $proposals,
            'proposalStatuses' => $this->proposalStatusOptions(),
            'tripThemes' => ['Honeymoon', 'Safari', 'Business', 'Family', 'Group', 'Adventure', 'Beach', 'Custom'],
            'invoices' => $record->converted_to_quote_id ? DB::table('quotation_payments')->where('quotation_id', $record->converted_to_quote_id)->latest('paid_at')->get() : collect(),
            'movements' => $record->converted_to_quote_id ? DB::table('quotation_days')->where('quotation_id', $record->converted_to_quote_id)->orderBy('day_number')->get() : collect(),
            'reservationEmails' => DB::table('reservation_emails')
                ->join('reservations', 'reservations.id', '=', 'reservation_emails.reservation_id')
                ->join('quotations', 'quotations.id', '=', 'reservations.quotation_id')
                ->where(function ($query) use ($record) { $query->where('quotations.request_id', $record->id)->orWhere('quotations.id', $record->converted_to_quote_id); })
                ->select('reservation_emails.*')->latest('reservation_emails.created_at')->get(),
            'flightRequests' => DB::table('flight_bookings')->where('request_reference', $record->request_number)->orderBy('departure_at')->get(),
        ]);
    }

    public function updateWorkspaceStatus(Request $request, int $id): JsonResponse
    {
        $record = RequestModel::findOrFail($id);
        abort_unless($request->user()?->can('update', $record), 403);
        $data = $request->validate(['status' => ['required', 'in:'.implode(',', array_keys(RequestModel::workspaceStatusOptions()))]]);
        $current = $record->workspaceStatus();
        if (!$this->isSequentialJourneyChange($current, $data['status'])) {
            return response()->json(['message' => 'The customer journey must follow the stages in order. Complete the current stage before moving forward.'], 422);
        }
        $stored = RequestModel::storedStatusForWorkspace($data['status']);
        $this->service->updateStatus($record, $stored, 'Workspace status changed to '.RequestModel::workspaceStatusOptions()[$data['status']]);

        return response()->json(['success' => true, 'status' => $data['status'], 'label' => RequestModel::workspaceStatusOptions()[$data['status']]]);
    }

    public function storeProposal(Request $request, int $id): RedirectResponse
    {
        $record = RequestModel::findOrFail($id);
        abort_unless($request->user()?->can('update', $record), 403);
        $data = $request->validate(['trip_theme' => ['required', 'string', 'max:80']]);
        $quotationId = DB::transaction(function () use ($record, $data) {
            $duration = max(1, (int) ($record->nights ?: ($record->arrival_date && $record->departure_date ? Carbon::parse($record->arrival_date)->diffInDays(Carbon::parse($record->departure_date)) : 7)));
            $id = DB::table('quotations')->insertGetId([
                'request_id' => $record->id,
                'client_id' => $this->ensureClientForRequest($record),
                'reference' => $this->nextQuotationReference(),
                'title' => $data['trip_theme'].' - '.($record->destination ?: $record->client_name),
                'trip_theme' => $data['trip_theme'],
                'start_date' => $record->arrival_date ?: now()->addMonths(3)->toDateString(),
                'duration_days' => $duration,
                'guest_count' => max(1, (int) $record->adults + (int) $record->children + (int) $record->infants),
                'start_location' => $record->destination ?: 'Africa',
                'currency' => $record->currency ?: 'USD',
                'office_markup_percent' => 20, 'misc_markup_percent' => 5, 'exchange_rate' => 1,
                'buy_total' => 0, 'sell_total' => 0, 'margin_total' => 0,
                'status' => 'draft', 'frozen' => false, 'created_at' => now(), 'updated_at' => now(),
            ]);
            for ($day = 1; $day <= $duration; $day++) {
                DB::table('quotation_days')->insert([
                    'quotation_id' => $id, 'day_number' => $day,
                    'travel_date' => Carbon::parse($record->arrival_date ?: now()->addMonths(3))->addDays($day - 1)->toDateString(),
                    'from_location' => $day === 1 ? $record->destination : null,
                    'to_location' => null, 'description' => null, 'created_at' => now(), 'updated_at' => now(),
                ]);
            }
            DB::table('proposal_workflows')->insert([
                'quotation_id' => $id, 'seller_id' => $record->assigned_to ?: auth()->id(),
                'country' => $record->country ?: 'Tanzania', 'proposal_type' => 'Itinerary',
                'created_at' => now(), 'updated_at' => now(),
            ]);
            if ($record->workspaceStatus() === 'new') $this->service->updateStatus($record, 'contacted', 'First proposal created; request moved to Existing.');
            return $id;
        });

        return redirect()->route('admin.requests.show', ['request' => $record->id, 'section' => 'proposals'])->with('success', 'Proposal created in Planning status.');
    }

    public function updateProposalStatus(Request $request, int $id, int $quotation): JsonResponse
    {
        $record = RequestModel::findOrFail($id);
        abort_unless($request->user()?->can('update', $record), 403);
        $data = $request->validate(['status' => ['required', 'in:'.implode(',', array_keys($this->proposalStatusOptions()))]]);
        $proposal = DB::table('quotations')->where('id', $quotation)->where('request_id', $record->id)->first();
        abort_unless($proposal, 404);
        $stored = $this->storedProposalStatus($data['status']);
        $currentProposalStatus = $this->proposalStatusKey($proposal->status);
        if (!$this->isSequentialJourneyChange($currentProposalStatus, $data['status'], ['new', 'planning', 'quotation_check', 'preconfirmed', 'confirmed', 'operated', 'dodo'])) {
            return response()->json(['message' => 'The proposal journey must follow the stages in order. Complete the current stage before moving forward.'], 422);
        }
        $updates = ['status' => $stored, 'updated_at' => now()];
        if ($data['status'] === 'preconfirmed') $updates['pre_confirmed_at'] = now();
        if ($data['status'] === 'confirmed') $updates['confirmation_date'] = now();
        if ($data['status'] === 'cancelled') $updates['cancellation_date'] = now();
        DB::transaction(function () use ($record, $proposal, $updates, $data, $request) {
            DB::table('quotations')->where('id', $proposal->id)->update($updates);
            $this->syncRequestStatusFromProposal($record->fresh(), $data['status']);
            ProposalWorkspaceController::capture($proposal->id, $request->user()?->id, 'Request workspace · proposal status '.$proposal->status.' → '.$data['status']);
        });

        return response()->json(['success' => true, 'status' => $data['status'], 'label' => $this->proposalStatusOptions()[$data['status']]]);
    }

    public function duplicateProposal(Request $request, int $id, int $quotation): RedirectResponse|JsonResponse
    {
        $record = RequestModel::findOrFail($id);
        abort_unless($request->user()?->can('update', $record), 403);
        $source = DB::table('quotations')->where('id', $quotation)->where('request_id', $record->id)->first();
        abort_unless($source, 404);
        $newId = DB::transaction(function () use ($source) {
            $attributes = (array) $source;
            unset($attributes['id']);
            $attributes['reference'] = $this->nextQuotationReference();
            $attributes['status'] = 'draft';
            foreach (['pre_confirmed_at', 'confirmation_date', 'cancellation_date'] as $reset) { $attributes[$reset] = null; }
            $attributes['created_at'] = now(); $attributes['updated_at'] = now();
            $newId = DB::table('quotations')->insertGetId($attributes);
            $workflow = DB::table('proposal_workflows')->where('quotation_id', $source->id)->first();
            if ($workflow) { $workflowData = (array) $workflow; unset($workflowData['id']); $workflowData['quotation_id'] = $newId; $workflowData['created_at'] = now(); $workflowData['updated_at'] = now(); DB::table('proposal_workflows')->insert($workflowData); }
            return $newId;
        });
        $reference = DB::table('quotations')->where('id', $newId)->value('reference');
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Proposal duplicated as '.$reference.'.']);
        }
        return back()->with('success', 'Proposal duplicated as '.$reference.'.');
    }

    public function deleteProposal(Request $request, int $id, int $quotation): RedirectResponse|JsonResponse
    {
        $record = RequestModel::findOrFail($id);
        abort_unless($request->user()?->can('update', $record), 403);
        $proposal = DB::table('quotations')->where('id', $quotation)->where('request_id', $record->id)->first();
        abort_unless($proposal, 404);
        if (in_array($proposal->status, ['confirmed', 'in_progress', 'completed', 'cancelled', 'dodo'], true) && $request->user()->role !== 'administrator') {
            if ($request->ajax()) {
                return response()->json(['message' => 'This proposal cannot be deleted at its current status.'], 403);
            }
            abort(403, 'This proposal cannot be deleted at its current status.');
        }
        DB::table('quotations')->where('id', $quotation)->delete();
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Proposal deleted.']);
        }
        return back()->with('success', 'Proposal deleted.');
    }

    private function proposalsForRequest(RequestModel $record)
    {
        $reservationPeople = DB::table('reservations')
            ->select('quotation_id', DB::raw('MAX(assigned_person) as reservation_person'))
            ->groupBy('quotation_id');

        return DB::table('quotations')
            ->leftJoin('proposal_workflows', 'proposal_workflows.quotation_id', '=', 'quotations.id')
            ->leftJoin('users as sellers', 'sellers.id', '=', 'proposal_workflows.seller_id')
            ->leftJoin('users as preconfirmed_users', 'preconfirmed_users.id', '=', 'quotations.pre_confirmed_by')
            ->leftJoinSub($reservationPeople, 'reservation_people', 'reservation_people.quotation_id', '=', 'quotations.id')
            ->where(function ($query) use ($record) { $query->where('quotations.request_id', $record->id)->orWhere('quotations.id', $record->converted_to_quote_id); })
            ->select('quotations.*', 'proposal_workflows.country', 'proposal_workflows.quotation_checked_at', 'proposal_workflows.leader_checked_at', 'proposal_workflows.seller_id', 'sellers.name as seller_name', 'preconfirmed_users.name as preconfirmed_by_name', 'reservation_people.reservation_person')
            ->orderByDesc('quotations.updated_at')->get();
    }

    private function proposalStatusOptions(): array
    {
        return ['new' => 'New', 'planning' => 'Planning', 'quotation_check' => 'Quotation Check', 'preconfirmed' => 'Pre-Confirmed', 'confirmed' => 'Confirmed', 'operated' => 'Operated', 'cancelled' => 'Cancelled', 'dodo' => 'DODO'];
    }

    private function storedProposalStatus(string $status): string
    {
        return match ($status) { 'new', 'planning' => 'draft', 'quotation_check' => 'sent', 'preconfirmed' => 'accepted', 'operated' => 'completed', default => $status };
    }

    private function proposalStatusKey(string $status): string
    {
        return match ($status) {
            'draft', 'active' => 'planning',
            'sent' => 'quotation_check',
            'accepted' => 'preconfirmed',
            'confirmed' => 'confirmed',
            'in_progress', 'completed' => 'operated',
            'cancelled' => 'cancelled',
            'dodo' => 'dodo',
            default => 'planning',
        };
    }

    private function isSequentialJourneyChange(string $current, string $target, ?array $stages = null): bool
    {
        if ($current === $target) return true;
        if ($target === 'cancelled' && !in_array($current, ['cancelled', 'dodo'], true)) return true;
        if ($current === 'cancelled' || $current === 'dodo') return false;
        $stages ??= ['new', 'existing', 'preconfirmed', 'confirmed', 'operated', 'dodo'];
        $currentIndex = array_search($current, $stages, true);
        $targetIndex = array_search($target, $stages, true);
        return $currentIndex !== false && $targetIndex !== false && $targetIndex === $currentIndex + 1;
    }

    private function nextQuotationReference(): string
    {
        $next = (int) DB::table('quotations')->max('id') + 1;
        return 'QT-'.now()->format('Y').'-'.str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

    private function ensureClientForRequest(RequestModel $record): int
    {
        if ($record->client_id) return (int) $record->client_id;
        $client = DB::table('clients')->where('email', $record->client_email)->first()
            ?? DB::table('clients')->where('name', $record->client_name)->first();
        if ($client) return (int) $client->id;
        return (int) DB::table('clients')->insertGetId([
            'name' => $record->client_name,
            'email' => $record->client_email ?: 'request-'.$record->request_number.'@shishifootsteps.com',
            'phone' => $record->client_phone,
            'country' => $record->country,
            'preferred_language' => $record->language ?: 'en',
            'status' => 'active',
            'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    private function syncRequestStatusFromProposal(RequestModel $record, string $status): void
    {
        $target = match ($status) { 'planning', 'quotation_check' => 'existing', 'preconfirmed' => 'preconfirmed', 'confirmed' => 'confirmed', 'operated' => 'operated', 'cancelled' => 'cancelled', 'dodo' => 'dodo', default => null };
        if (!$target) return;
        $current = array_search($record->workspaceStatus(), array_keys(RequestModel::workspaceStatusOptions()), true);
        $next = array_search($target, array_keys(RequestModel::workspaceStatusOptions()), true);
        if ($target !== 'cancelled' && $target !== 'dodo' && $current !== false && $next !== false && $current >= $next) return;
        $this->service->updateStatus($record, RequestModel::storedStatusForWorkspace($target), 'Proposal status synchronized to '.$target.'.');
    }

    public function edit(int $id): View
    {
        $record = $this->service->find($id);
        abort_unless($record, 404);

        return view('admin.requests.edit', [
            'request' => $record,
            'clients' => \App\Models\Client::query()->orderBy('name')->get(['id', 'name', 'email', 'phone', 'country']),
            'users' => \App\Models\User::query()->where('is_active', true)->orderBy('name')->get(['id', 'name']),
            'statuses' => array_keys(RequestModel::statusOptions()),
            'statusOptions' => RequestModel::statusOptions(),
            'countries' => $this->worldCountries(),
            'sources' => ['manual', 'website', 'whatsapp', 'email', 'walk_in', 'api', 'referral', 'phone'],
            'travelTypes' => ['safari', 'beach', 'city', 'adventure', 'cultural', 'honeymoon', 'family', 'business', 'group'],
            'accommodationTiers' => ['luxury', 'midrange', 'budget', 'camping'],
            'priorities' => ['low', 'medium', 'high', 'urgent'],
        ]);
    }

    public function update(UpdateRequestRequest $request, int $id): RedirectResponse
    {
        $record = RequestModel::findOrFail($id);
        $this->service->update($record, $request->validated());

        return redirect()->route('admin.requests.show', $id)
            ->with('success', 'Request updated successfully.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $record = RequestModel::findOrFail($id);
        $this->service->delete($record);

        return redirect()->route('admin.requests.index')
            ->with('success', 'Request archived successfully.');
    }

    public function restore(int $id): RedirectResponse
    {
        $this->service->restore($id);

        return redirect()->route('admin.requests.show', $id)
            ->with('success', 'Request restored successfully.');
    }

    public function datatable(Request $request): JsonResponse
    {
        $filters = $request->only([
            'search', 'status', 'source', 'language', 'company', 'country', 'assigned_to', 'request_types',
            'followup_from', 'followup_to',
            'date_from', 'date_to', 'arrival_from', 'arrival_to',
            'destination', 'accommodation_tier', 'travel_type', 'priority',
            'rating', 'is_diamond', 'flag_color', 'sort', 'direction',
        ]);

        $perPage = (int) $request->input('per_page', 25);
        $paginator = $this->filterService->apply($filters)
            ->with(['client', 'assignedUser', 'consultant'])
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        $rows = $paginator->getCollection()->map(function ($record) {
            $canConvert = !$record->converted_to_quote_id && !in_array($record->status, ['cancelled', 'archived']);
            $template = $record->itineraryTemplate;
            $itineraryName = $template?->trip_name ?: $template?->name;

            return [
                'id' => $record->id,
                'checkbox' => view('admin.requests.partials.checkbox', ['id' => $record->id])->render(),
                'request_number' => $record->request_number,
                'follow_up_date' => $record->followups->first()?->followup_date?->format('Y-m-d H:i'),
                'arrival_date' => $record->arrival_date?->format('Y-m-d'),
                'created_date' => $record->created_at?->format('Y-m-d'),
                'client_first_name' => $record->client?->name ?? $record->client_name,
                'client_last_name' => '',
                'nationality' => $record->nationality,
                'email' => $record->client_email,
                'phone' => $record->client_phone,
                'status' => $record->status,
                'status_label' => $record->status_label,
                'quote_value' => $record->quote_value,
                'rating' => $record->rating,
                'seller_notes' => Str::limit($record->seller_notes, 100),
                'is_diamond' => $record->is_diamond,
                'source' => $record->source,
                'language' => $record->language,
                'assigned_consultant' => $record->assignedUser?->name,
                'company' => $record->company,
                'current_stage' => $record->status_label,
                'destination' => $record->destination,
                'priority' => $record->priority,
                'flag_color' => $record->flag_color,
                'nights' => $record->nights,
                'adults' => $record->adults,
                'children' => $record->children,
                'budget' => $record->budget,
                'currency' => $record->currency,
                'itinerary_template_id' => $template?->id,
                'itinerary_template_name' => $itineraryName,
                'can_convert' => $canConvert,
                'converted_to_quote_id' => $record->converted_to_quote_id,
                'actions' => view('admin.requests.partials.actions', [
                    'record' => $record,
                    'canConvert' => $canConvert,
                ])->render(),
            ];
        });

        return response()->json([
            'data' => $rows,
            'total' => $paginator->total(),
            'per_page' => $paginator->perPage(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
        ]);
    }

    public function filters(Request $request): JsonResponse
    {
        $filters = $request->only([
            'search', 'status', 'source', 'language', 'company', 'country', 'assigned_to', 'request_types',
            'followup_from', 'followup_to',
            'date_from', 'date_to', 'arrival_from', 'arrival_to',
            'destination', 'accommodation_tier', 'travel_type', 'priority',
            'rating', 'is_diamond', 'flag_color', 'sort', 'direction',
        ]);

        $records = $this->service->list($filters, (int) $request->input('per_page', 20));

        $html = view('admin.requests.partials._table_rows', ['requests' => $records])->render();

        return response()->json([
            'html' => $html,
            'total' => $records->total(),
            'from' => $records->firstItem(),
            'to' => $records->lastItem(),
            'last_page' => $records->lastPage(),
        ]);
    }

    public function searchClients(Request $request): JsonResponse
    {
        $term = $request->input('term', '');
        $clients = $this->service->searchClients($term);

        return response()->json($clients->map(fn ($client) => [
            'id' => $client->id,
            'name' => $client->name,
            'email' => $client->email,
            'phone' => $client->phone,
            'country' => $client->country,
        ]));
    }

    public function storeClient(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'unique:clients,email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'country' => ['nullable', 'string', 'max:100'],
        ]);

        $client = $this->service->createClient($validated);

        return response()->json([
            'id' => $client->id,
            'name' => $client->name,
            'email' => $client->email,
            'phone' => $client->phone,
            'country' => $client->country,
        ]);
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $record = RequestModel::findOrFail($id);
        $this->service->updateStatus($record, $validated['status'], $validated['notes'] ?? null);

        return response()->json([
            'success' => true,
            'status' => $record->fresh()->status,
            'status_label' => $record->fresh()->status_label,
        ]);
    }

    public function updateRating(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        $record = RequestModel::findOrFail($id);
        $this->service->updateRating($record, $validated['rating']);

        return response()->json(['success' => true, 'rating' => $validated['rating']]);
    }

    public function updateFlag(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'color' => ['nullable', 'string', 'max:20'],
        ]);

        $record = RequestModel::findOrFail($id);
        $this->service->updateFlagColor($record, $validated['color']);

        return response()->json(['success' => true, 'flag_color' => $validated['color']]);
    }

    public function addNote(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'note' => ['required', 'string', 'max:5000'],
            'type' => ['nullable', 'string', 'max:50'],
        ]);

        $record = RequestModel::findOrFail($id);
        $note = $this->service->addNote($record, $validated);

        return response()->json([
            'success' => true,
            'id' => $note->id,
            'note' => $note->note,
            'user' => auth()->user()->name,
            'created_at' => $note->created_at?->diffForHumans(),
        ]);
    }

    public function getNotes(Request $request, int $id): JsonResponse
    {
        $record = RequestModel::findOrFail($id);
        $notes = $record->notes()->with('user')->latest('created_at')->get();

        $html = view('admin.requests.partials.notes_list', ['notes' => $notes])->render();

        return response()->json(['html' => $html]);
    }

    public function addTask(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'assigned_to' => ['nullable', 'integer', 'exists:users,id'],
            'deadline' => ['nullable', 'date'],
            'priority' => ['nullable', 'string', 'in:low,medium,high,urgent'],
        ]);

        $record = RequestModel::findOrFail($id);
        $task = $this->service->addTask($record, $validated);

        return response()->json([
            'success' => true,
            'id' => $task->id,
            'title' => $task->title,
            'priority' => $task->priority,
            'deadline' => $task->deadline?->format('Y-m-d'),
            'assigned_to' => $task->assignedUser?->name,
        ]);
    }

    public function completeTask(Request $request, int $id, int $taskId): JsonResponse
    {
        $task = RequestTask::where('request_id', $id)->findOrFail($taskId);
        $this->service->completeTask($task);

        return response()->json(['success' => true]);
    }

    public function getTasks(Request $request, int $id): JsonResponse
    {
        $record = RequestModel::findOrFail($id);
        $tasks = $record->tasks()->with('assignedUser')->latest()->get();

        $html = view('admin.requests.partials.tasks_list', ['tasks' => $tasks])->render();

        return response()->json(['html' => $html]);
    }

    public function addFollowup(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'followup_date' => ['required', 'date'],
            'reminder_type' => ['nullable', 'string', 'max:50'],
        ]);

        $record = RequestModel::findOrFail($id);
        $followup = $this->service->addFollowup($record, $validated);

        return response()->json([
            'success' => true,
            'id' => $followup->id,
            'title' => $followup->title,
            'followup_date' => $followup->followup_date->format('Y-m-d H:i'),
        ]);
    }

    public function uploadFile(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'max:10240'],
            'category' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $record = RequestModel::findOrFail($id);
        $file = $this->service->addFile($record, $validated);

        return response()->json([
            'success' => true,
            'id' => $file->id,
            'original_name' => $file->original_name,
            'file_size' => $file->file_size,
            'category' => $file->category,
        ]);
    }

    public function getTimeline(Request $request, int $id): JsonResponse
    {
        $record = RequestModel::findOrFail($id);
        $history = $record->history()->with('user')->latest('created_at')->get();
        $statusLogs = $record->statusLogs()->with('user')->latest('created_at')->get();

        $html = view('admin.requests.partials.timeline', [
            'history' => $history,
            'statusLogs' => $statusLogs,
        ])->render();

        return response()->json(['html' => $html]);
    }

    public function convertToQuote(Request $request, int $id): JsonResponse
    {
        $record = RequestModel::findOrFail($id);

        if ($record->converted_to_quote_id) {
            return response()->json([
                'success' => false,
                'message' => 'This request has already been converted to a quotation.',
                'redirect' => route('admin.quotations.show', $record->converted_to_quote_id),
            ], 422);
        }

        if ($request->input('itinerary_template_id')) {
            $record->itinerary_template_id = $request->input('itinerary_template_id');
            $record->save();
        }

        try {
            $quotationId = $this->service->convertToQuote($record);

            return response()->json([
                'success' => true,
                'message' => 'Request converted to quotation successfully.',
                'redirect' => route('admin.quotations.show', $quotationId),
                'quotation_id' => $quotationId,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to convert request: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateTemplate(Request $request, int $id): JsonResponse
    {
        $record = RequestModel::findOrFail($id);
        $record->itinerary_template_id = $request->input('itinerary_template_id');
        $record->save();

        return response()->json(['success' => true]);
    }

    public function bulkAction(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:requests,id'],
            'action' => ['required', 'string', 'in:delete,status,archive'],
            'value' => ['nullable', 'string', 'max:255'],
        ]);

        $ids = $validated['ids'];
        $action = $validated['action'];
        $value = $validated['value'] ?? null;

        DB::transaction(function () use ($ids, $action, $value) {
            $records = RequestModel::withTrashed()->whereIn('id', $ids)->get();

            foreach ($records as $record) {
                match ($action) {
                    'delete' => $record->delete(),
                    'archive' => $record->delete(),
                    'status' => $this->service->updateStatus($record, $value),
                    default => null,
                };
            }
        });

        $count = count($ids);
        $message = match ($action) {
            'delete' => "{$count} request(s) archived.",
            'archive' => "{$count} request(s) archived.",
            'status' => "{$count} request(s) status updated to {$value}.",
            default => 'Action completed.',
        };

        return response()->json(['success' => true, 'message' => $message]);
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $filters = $request->only([
            'search', 'status', 'source', 'language', 'company', 'country', 'assigned_to', 'request_types',
            'followup_from', 'followup_to',
            'date_from', 'date_to', 'arrival_from', 'arrival_to',
            'destination', 'accommodation_tier', 'travel_type', 'priority',
            'rating', 'is_diamond', 'flag_color', 'sort', 'direction',
        ]);

        $records = $this->filterService->apply($filters)->latest()->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="requests-' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($records) {
            $output = fopen('php://output', 'w');

            fputcsv($output, [
                'Request Number', 'Client Name', 'Email', 'Phone', 'Nationality', 'Country',
                'Destination', 'Arrival Date', 'Departure Date', 'Nights', 'Adults', 'Children',
                'Status', 'Priority', 'Source', 'Language', 'Company', 'Budget', 'Currency',
                'Quote Value', 'Rating', 'Diamond', 'Assigned To', 'Created At',
            ]);

            foreach ($records as $record) {
                fputcsv($output, [
                    $record->request_number,
                    $record->client_name,
                    $record->client_email,
                    $record->client_phone,
                    $record->nationality,
                    $record->country,
                    $record->destination,
                    $record->arrival_date?->format('Y-m-d'),
                    $record->departure_date?->format('Y-m-d'),
                    $record->nights,
                    $record->adults,
                    $record->children,
                    $record->status,
                    $record->priority,
                    $record->source,
                    $record->language,
                    $record->company,
                    $record->budget,
                    $record->currency,
                    $record->quote_value,
                    $record->rating,
                    $record->is_diamond ? 'Yes' : 'No',
                    $record->assignedUser?->name,
                    $record->created_at?->format('Y-m-d H:i'),
                ]);
            }

            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportExcel(Request $request): StreamedResponse
    {
        return $this->exportCsv($request);
    }

    public function stats(): JsonResponse
    {
        $widgets = $this->service->getDashboardWidgets();
        $stats = $this->service->getStats();

        return response()->json([
            'widgets' => $widgets,
            'stats' => $stats,
        ]);
    }

    private function worldCountries(): array
    {
        if (class_exists(\Symfony\Component\Intl\Countries::class)) {
            return \Symfony\Component\Intl\Countries::getNames('en');
        }

        if (class_exists(\Locale::class)) {
            $codes = preg_split('/\s+/', 'AD AE AF AG AI AL AM AO AQ AR AS AT AU AW AX AZ BA BB BD BE BF BG BH BI BJ BL BM BN BO BQ BR BS BT BV BW BY BZ CA CC CD CF CG CH CI CK CL CM CN CO CR CU CV CW CX CY CZ DE DJ DK DM DO DZ EC EE EG EH ER ES ET FI FJ FK FM FO FR GA GB GD GE GF GG GH GI GL GM GN GP GQ GR GS GT GU GW GY HK HM HN HR HT HU ID IE IL IM IN IO IQ IR IS IT JE JM JO JP KE KG KH KI KM KN KP KR KW KY KZ LA LB LC LI LK LR LS LT LU LV LY MA MC MD ME MF MG MH MK ML MM MN MO MP MQ MR MS MT MU MV MW MX MY MZ NA NC NE NF NG NI NL NO NP NR NU NZ OM PA PE PF PG PH PK PL PM PN PR PS PT PW PY QA RE RO RS RU RW SA SB SC SD SE SG SH SI SJ SK SL SM SN SO SR SS ST SV SX SY SZ TC TD TF TG TH TJ TK TL TM TN TO TR TR TT TV TW TZ UA UG UM US UY UZ VA VC VE VG VI VN VU WF WS YE YT ZA ZM ZW XK');

            return collect($codes)->mapWithKeys(function ($code) {
                return [$code => \Locale::getDisplayRegion('und_'.$code, 'en') ?: $code];
            })->sort()->all();
        }

        return \App\Models\Country::query()->orderBy('name')->pluck('name', 'code')->all();
    }
}
