<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Request;
use App\Models\RequestFile;
use App\Models\RequestFlag;
use App\Models\RequestFollowup;
use App\Models\RequestNote;
use App\Models\RequestTask;
use App\Repositories\RequestRepository;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RequestService
{
    public function __construct(
        private RequestRepository $repository
    ) {}

    public function getStats(): array
    {
        return $this->repository->getStats();
    }

    public function list(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        return $this->repository->paginate($filters, $perPage);
    }

    public function find(int $id): ?Request
    {
        return $this->repository->find($id);
    }

    public function create(array $data): Request
    {
        return $this->repository->create($data);
    }

    public function update(Request $request, array $data): Request
    {
        return $this->repository->update($request, $data);
    }

    public function updateStatus(Request $request, string $status, ?string $notes = null): Request
    {
        return $this->repository->updateStatus($request, $status, $notes);
    }

    public function delete(Request $request): bool
    {
        return $request->delete();
    }

    public function restore(int $id): bool
    {
        $request = Request::onlyTrashed()->find($id);
        if (!$request) {
            return false;
        }
        return $request->restore();
    }

    public function convertToQuote(Request $request): int
    {
        return DB::transaction(function () use ($request) {
            $guestCount = ($request->adults ?? 0) + ($request->children ?? 0);
            $startDate = $request->arrival_date ?? now()->addMonths(3)->toDateString();
            $destination = $request->destination ?: 'Tailor-made East Africa';
            $currency = $request->currency ?? 'USD';

            $template = null;
            if ($request->itinerary_template_id) {
                $template = \App\Models\ItineraryTemplate::with(['days.destination', 'days.hotel', 'days.activities', 'pricing'])->find($request->itinerary_template_id);
            }
            $durationDays = $template ? $template->duration_days : max(1, $request->nights ?? 7);

            $reference = 'QT-' . now()->format('Y') . '-' . str_pad(
                (string) (DB::table('quotations')->count() + 1),
                4,
                '0',
                STR_PAD_LEFT
            );

            $buyTotal = 0;
            $sellTotal = 0;

            if ($template && $template->pricing->count()) {
                $matchedPricing = $template->pricing->firstWhere('currency', $currency) ?? $template->pricing->first();
                $buyTotal = $matchedPricing->total_cost ?? 0;
                $sellTotal = ($matchedPricing->price_per_person ?? 0) * max(1, $guestCount ?: 2) + ($matchedPricing->single_supplement ?? 0);
                $currency = $matchedPricing->currency;
            }

            $quotationId = DB::table('quotations')->insertGetId([
                'client_id' => $request->client_id,
                'request_id' => $request->id,
                'reference' => $reference,
                'title' => $template ? ($template->trip_name ?? $template->name) : "{$destination} Safari for {$request->client_name}",
                'start_date' => $startDate,
                'duration_days' => $durationDays,
                'guest_count' => max(1, $guestCount),
                'start_location' => $destination,
                'currency' => $currency,
                'office_markup_percent' => 20,
                'misc_markup_percent' => 5,
                'exchange_rate' => 1,
                'buy_total' => $buyTotal,
                'sell_total' => $sellTotal,
                'margin_total' => max(0, $sellTotal - $buyTotal),
                'status' => 'draft',
                'frozen' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($template && $template->days->count()) {
                foreach ($template->days as $tDay) {
                    $travelDate = $tDay->date
                        ? Carbon::parse($tDay->date)
                        : Carbon::parse($startDate)->addDays($tDay->day_number - 1);

                    $qDayId = DB::table('quotation_days')->insertGetId([
                        'quotation_id' => $quotationId,
                        'day_number' => $tDay->day_number,
                        'travel_date' => $travelDate->toDateString(),
                        'from_location' => $tDay->destination?->name ?? $tDay->destination,
                        'to_location' => $tDay->destination?->name ?? $tDay->destination,
                        'description' => $tDay->description,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $isDeparture = $tDay->hotel && (str_contains(strtolower($tDay->hotel->name), 'departure') || str_contains(strtolower($tDay->hotel_name ?? ''), 'departure'));
                    $roomBuy = $isDeparture ? 0 : 600;
                    $roomMarkup = $tDay->hotel?->default_markup_percent ?? 20;
                    $roomSell = $roomBuy > 0 ? round($roomBuy * (1 + $roomMarkup / 100), 2) : 0;
                    $guestQty = max(1, $guestCount) ?: 2;
                    $actBuy = 100;
                    $actMarkup = 20;
                    $actSell = round($actBuy * (1 + $actMarkup / 100), 2);

                    $activitiesText = array_filter([$tDay->morning_activity, $tDay->afternoon_activity, $tDay->evening_activity]);
                    foreach ($activitiesText as $i => $actText) {
                        DB::table('quotation_items')->insert([
                            'quotation_day_id' => $qDayId,
                            'item_type' => 'activity',
                            'title' => $actText,
                            'source' => $tDay->destination?->name ?? $destination,
                            'calculation_type' => 'per_person',
                            'quantity' => $guestQty,
                            'buy_unit_price' => $actBuy,
                            'markup_percent' => $actMarkup,
                            'sell_unit_price' => $actSell,
                            'buy_total' => $actBuy * $guestQty,
                            'sell_total' => $actSell * $guestQty,
                            'currency' => $currency,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    if ($tDay->hotel || $tDay->hotel_name) {
                        $hotelName = $tDay->hotel?->name ?? $tDay->hotel_name;
                        DB::table('quotation_items')->insert([
                            'quotation_day_id' => $qDayId,
                            'item_type' => 'room',
                            'source_id' => $tDay->hotel_id,
                            'title' => $hotelName . ($tDay->room_type ? " ({$tDay->room_type})" : ''),
                            'source' => $hotelName,
                            'calculation_type' => 'per_room',
                            'quantity' => 1,
                            'buy_unit_price' => $roomBuy,
                            'markup_percent' => $roomMarkup,
                            'sell_unit_price' => $roomSell,
                            'buy_total' => $roomBuy,
                            'sell_total' => $roomSell,
                            'currency' => $currency,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    foreach ($tDay->activities as $tAct) {
                        $tActBuy = $tAct->price ?? $actBuy;
                        $tActSell = $tAct->price ? round($tAct->price * (1 + $roomMarkup / 100), 2) : $actSell;
                        DB::table('quotation_items')->insert([
                            'quotation_day_id' => $qDayId,
                            'item_type' => 'activity',
                            'title' => $tAct->activity_name ?? $tAct->activity?->name ?? 'Activity',
                            'source' => $tAct->activity?->name ?? '',
                            'calculation_type' => 'per_person',
                            'quantity' => $guestQty,
                            'buy_unit_price' => $tActBuy,
                            'markup_percent' => $tAct->price ? $roomMarkup : $actMarkup,
                            'sell_unit_price' => $tActSell,
                            'buy_total' => $tActBuy * $guestQty,
                            'sell_total' => $tActSell * $guestQty,
                            'currency' => $currency,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            } else {
                for ($day = 1; $day <= $durationDays; $day++) {
                    DB::table('quotation_days')->insert([
                        'quotation_id' => $quotationId,
                        'day_number' => $day,
                        'travel_date' => Carbon::parse($startDate)->addDays($day - 1)->toDateString(),
                        'from_location' => $day === 1 ? $destination : null,
                        'to_location' => null,
                        'description' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            $sellerId = Auth::id() ?: DB::table('users')->where('is_active', true)->whereIn('role', ['sales', 'administrator'])->value('id');

            DB::table('proposal_workflows')->insert([
                'quotation_id' => $quotationId,
                'seller_id' => $sellerId,
                'country' => $request->country ?: 'Tanzania',
                'proposal_type' => 'Itinerary',
                'client_token' => Str::random(64),
                'client_link_enabled' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $request->update([
                'status' => 'converted',
                'converted_to_quote_id' => $quotationId,
                'converted_to_quote_at' => now(),
            ]);

            return $quotationId;
        });
    }

    public function searchClients(string $term): Collection
    {
        return $this->repository->searchClients($term);
    }

    public function createClient(array $data): Client
    {
        return $this->repository->upsertClient($data);
    }

    public function addNote(Request $request, array $data): RequestNote
    {
        return $request->notes()->create([
            'user_id' => Auth::id(),
            'note' => $data['note'],
            'type' => $data['type'] ?? 'general',
        ]);
    }

    public function addTask(Request $request, array $data): RequestTask
    {
        return $request->tasks()->create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'assigned_to' => $data['assigned_to'] ?? null,
            'deadline' => $data['deadline'] ?? null,
            'priority' => $data['priority'] ?? 'medium',
            'status' => 'pending',
        ]);
    }

    public function completeTask(RequestTask $task): void
    {
        $task->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    public function addFile(Request $request, array $data): RequestFile
    {
        $file = $data['file'];
        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('requests/' . $request->id, $filename, 'public');

        return $request->files()->create([
            'user_id' => Auth::id(),
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'category' => $data['category'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);
    }

    public function addFollowup(Request $request, array $data): RequestFollowup
    {
        return $request->followups()->create([
            'user_id' => Auth::id(),
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'followup_date' => $data['followup_date'],
            'status' => 'pending',
            'reminder_type' => $data['reminder_type'] ?? null,
        ]);
    }

    public function addFlag(Request $request, string $color, ?string $note = null): RequestFlag
    {
        return $request->flags()->create([
            'user_id' => Auth::id(),
            'color' => $color,
            'note' => $note,
        ]);
    }

    public function updateRating(Request $request, int $rating): void
    {
        $request->update(['rating' => $rating]);
    }

    public function updateFlagColor(Request $request, ?string $color): void
    {
        $request->update(['flag_color' => $color]);
    }

    public function getDashboardWidgets(): array
    {
        $today = now()->toDateString();
        $user = Auth::user();

        return [
            'today_requests' => Request::query()
                ->whereDate('created_at', $today)
                ->count(),
            'pending_followups' => DB::table('request_followups')
                ->where('status', 'pending')
                ->whereDate('followup_date', '<=', now())
                ->count(),
            'new_requests' => Request::query()
                ->where('status', 'new')
                ->count(),
            'conversion_rate' => $this->calculateConversionRate(),
            'total_active' => Request::query()
                ->whereNotIn('status', ['cancelled', 'archived', 'converted'])
                ->count(),
            'my_tasks' => RequestTask::query()
                ->where('assigned_to', $user?->id)
                ->where('status', '!=', 'completed')
                ->count(),
            'overdue_tasks' => RequestTask::query()
                ->where('assigned_to', $user?->id)
                ->where('status', '!=', 'completed')
                ->where('deadline', '<', $today)
                ->count(),
            'recent_requests' => Request::query()
                ->with(['client', 'assignedUser'])
                ->latest()
                ->limit(5)
                ->get(),
            'upcoming_followups' => DB::table('request_followups')
                ->join('requests', 'requests.id', '=', 'request_followups.request_id')
                ->where('request_followups.status', 'pending')
                ->whereDate('request_followups.followup_date', '>=', $today)
                ->whereDate('request_followups.followup_date', '<=', now()->addDays(7))
                ->select('request_followups.*', 'requests.client_name', 'requests.request_number')
                ->orderBy('request_followups.followup_date')
                ->limit(5)
                ->get(),
        ];
    }

    private function calculateConversionRate(): float
    {
        $total = Request::query()->count();
        if ($total === 0) {
            return 0;
        }
        $converted = Request::query()->whereNotNull('converted_to_quote_id')->count();
        return round(($converted / $total) * 100, 1);
    }
}
