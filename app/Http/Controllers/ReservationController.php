<?php

namespace App\Http\Controllers;

use App\Services\AvailabilityService;
use App\Services\ReservationMailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReservationController extends Controller
{
    public function accommodationBookings(Request $request): View
    {
        $filters = $request->validate([
            'accommodation' => ['nullable', 'string', 'max:80'],
            'minimum_date' => ['nullable', 'date'],
            'maximum_date' => ['nullable', 'date', 'after_or_equal:minimum_date'],
        ]);

        $searched = $request->filled('minimum_date') || $request->filled('maximum_date') || $request->filled('accommodation');
        $bookings = collect();
        $totalBedNights = 0;

        if ($request->filled('minimum_date') && $request->filled('maximum_date')) {
            [$bookings, $totalBedNights] = $this->accommodationBookingResults($filters);
        }

        return view('admin.requests.accommodation-bookings', [
            'accommodations' => $this->accommodationBookingOptions(),
            'bookings' => $bookings,
            'totalBedNights' => $totalBedNights,
            'searched' => $searched,
            'filters' => $filters,
        ]);
    }

    public function exportAccommodationBookings(Request $request): StreamedResponse
    {
        $filters = $request->validate([
            'accommodation' => ['nullable', 'string', 'max:80'],
            'minimum_date' => ['required', 'date'],
            'maximum_date' => ['required', 'date', 'after_or_equal:minimum_date'],
        ]);
        [$bookings, $totalBedNights] = $this->accommodationBookingResults($filters);

        return response()->streamDownload(function () use ($bookings, $totalBedNights) {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['Date', 'Proposal', 'Amount of Persons', 'Nights', 'Bed Nights']);
            foreach ($bookings as $booking) {
                fputcsv($output, [$booking->booking_date, $booking->proposal_reference.' - '.$booking->proposal_title, $booking->persons, $booking->nights, $booking->bed_nights]);
            }
            fputcsv($output, ['', 'Total bed nights in selected period', '', '', $totalBedNights]);
            fclose($output);
        }, 'accommodation-bookings-'.now()->format('Y-m-d').'.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function accommodationBookingResults(array $filters): array
    {
        $rows = $this->accommodationBookingQuery($filters)->get();
        $minimum = Carbon::parse($filters['minimum_date'])->startOfDay();
        $maximum = Carbon::parse($filters['maximum_date'])->addDay()->startOfDay();
        $total = 0;

        $bookings = $rows->map(function ($booking) use ($minimum, $maximum, &$total) {
            $start = Carbon::parse($booking->starts_at)->startOfDay();
            $end = Carbon::parse($booking->ends_at)->startOfDay();
            $effectiveStart = $start->greaterThan($minimum) ? $start : $minimum;
            $effectiveEnd = $end->lessThan($maximum) ? $end : $maximum;
            $nights = max(0, $effectiveStart->diffInDays($effectiveEnd, false));
            $persons = max(1, (int) ($booking->guest_count ?: $booking->quantity ?: 1));
            $booking->booking_date = $start->toDateString();
            $booking->nights = $nights;
            $booking->persons = $persons;
            $booking->bed_nights = $nights * $persons;
            $total += $booking->bed_nights;

            return $booking;
        })->filter(fn ($booking) => $booking->nights > 0)->values();

        return [$bookings, $total];
    }

    private function accommodationBookingQuery(array $filters)
    {
        return DB::table('reservations')
            ->join('quotations', 'quotations.id', '=', 'reservations.quotation_id')
            ->leftJoin('quotation_items', 'quotation_items.id', '=', 'reservations.quotation_item_id')
            ->leftJoin('room_types', function ($join) {
                $join->on('room_types.id', '=', 'reservations.resource_id')
                    ->where('reservations.reservation_type', '=', 'room');
            })
            ->leftJoin('hotels', 'hotels.id', '=', 'room_types.hotel_id')
            ->where('reservations.reservation_type', 'room')
            ->whereNotIn('reservations.status', ['cancelled', 'rejected'])
            ->whereDate('reservations.starts_at', '<=', $filters['maximum_date'])
            ->whereDate('reservations.ends_at', '>=', $filters['minimum_date'])
            ->when($filters['accommodation'] ?? null, function ($query, $accommodation) {
                [$type, $id] = array_pad(explode(':', $accommodation, 2), 2, null);
                if ($type === 'room') {
                    $query->where('room_types.id', (int) $id);
                } elseif ($type === 'hotel') {
                    $query->where('hotels.id', (int) $id);
                } elseif ($type === 'proposal') {
                    $query->where('reservations.quotation_id', (int) $id);
                }
            })
            ->select([
                'reservations.starts_at', 'reservations.ends_at', 'reservations.quantity',
                'quotations.reference as proposal_reference', 'quotations.title as proposal_title',
                'quotations.guest_count', 'hotels.name as hotel_name', 'room_types.name as room_name',
            ])
            ->orderBy('reservations.starts_at')
            ->orderBy('quotations.reference');
    }

    private function accommodationBookingOptions(): array
    {
        $rooms = DB::table('room_types')
            ->join('hotels', 'hotels.id', '=', 'room_types.hotel_id')
            ->where('room_types.active', true)
            ->orderBy('hotels.name')->orderBy('room_types.name')
            ->get(['room_types.id', 'room_types.name as room_name', 'hotels.id as hotel_id', 'hotels.name as hotel_name']);
        $hotels = DB::table('hotels')->where('status', true)->orderBy('name')->get(['id', 'name']);
        $proposals = DB::table('quotations')
            ->join('reservations', 'reservations.quotation_id', '=', 'quotations.id')
            ->where('reservations.reservation_type', 'room')
            ->distinct()->orderBy('quotations.reference')
            ->get(['quotations.id', 'quotations.reference', 'quotations.title']);

        return compact('rooms', 'hotels', 'proposals');
    }

    public function index(Request $request): View
    {
        return view('admin.reservations.index', [
            'reservations' => DB::table('reservations')
                ->join('quotations', 'quotations.id', '=', 'reservations.quotation_id')
                ->join('clients', 'clients.id', '=', 'quotations.client_id')
                ->select('reservations.*', 'quotations.reference as quotation_reference', 'quotations.title as quotation_title', 'clients.name as client_name')
                ->when($request->filled('status'), fn ($query) => $query->where('reservations.status', $request->status))
                ->orderBy('reservations.starts_at')->paginate(25)->withQueryString(),
        ]);
    }

    public function store(Request $request, AvailabilityService $availability): RedirectResponse
    {
        $data = $request->validate([
            'quotation_id' => ['required', 'exists:quotations,id'],
            'quotation_item_id' => ['required', 'exists:quotation_items,id'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'quantity' => ['required', 'integer', 'min:1', 'max:100'],
            'supplier' => ['nullable', 'string', 'max:180'],
            'assigned_person' => ['nullable', 'string', 'max:180'],
            'number_plate' => ['nullable', 'string', 'max:40'],
            'payment_deadline' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:1500'],
        ]);
        $item = DB::table('quotation_items')->find($data['quotation_item_id']);
        abort_unless($item, 404);
        $type = $item->item_type === 'vehicle' ? 'vehicle' : ($item->item_type === 'room' ? 'room' : 'activity');

        if ($type === 'room' && ! $availability->roomAvailable((int) $item->source_id, $data['starts_at'], $data['ends_at'], (int) $data['quantity'])) {
            return back()->withErrors(['availability' => 'This room type is already fully booked for the selected dates. Choose another hotel or room category.']);
        }
        if ($type === 'vehicle' && ! $availability->vehicleAvailable((int) $item->source_id, $data['starts_at'], $data['ends_at'])) {
            return back()->withErrors(['availability' => 'This vehicle/number plate is already assigned to another client for the selected dates.']);
        }
        if ($type === 'activity') {
            $capacity = DB::table('tour_activities')->where('id', $item->source_id)->value('daily_capacity');
            $booked = DB::table('reservations')->where('reservation_type', 'activity')->where('resource_id', $item->source_id)
                ->whereDate('starts_at', Carbon::parse($data['starts_at'])->toDateString())
                ->whereIn('status', ['pending', 'confirmed'])->sum('quantity');
            if ($capacity && ($booked + $data['quantity']) > $capacity) {
                return back()->withErrors(['availability' => "The activity capacity is {$capacity}; this booking would exceed it."]);
            }
        }

        DB::table('reservations')->insert([
            ...$data, 'reservation_type' => $type, 'resource_id' => $item->source_id,
            'amount_due' => $item->buy_total, 'actual_cost' => $item->buy_total,
            'paid_amount' => 0, 'status' => 'pending',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        return back()->with('success', 'Reservation created. Availability is now held against this quotation.');
    }

    public function update(Request $request, int $reservation): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,requested,confirmed,rejected,cancelled,completed'],
            'confirmation_number' => ['nullable', 'string', 'max:120'],
            'assigned_person' => ['nullable', 'string', 'max:180'],
            'number_plate' => ['nullable', 'string', 'max:40'],
            'actual_cost' => ['required', 'numeric', 'min:0'],
            'paid_amount' => ['required', 'numeric', 'min:0'],
            'payment_deadline' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:1500'],
        ]);
        DB::table('reservations')->where('id', $reservation)->update([...$data, 'updated_at' => now()]);

        return back()->with('success', 'Reservation and supplier payment updated.');
    }

    public function destroy(int $reservation): RedirectResponse
    {
        DB::table('reservations')->where('id', $reservation)->delete();

        return back()->with('success', 'Reservation released and deleted.');
    }

    public function email(Request $request, int $reservation, ReservationMailService $mailService): RedirectResponse
    {
        $record = $mailService->reservation($reservation);
        abort_unless($record,404);
        $data = $request->validate(['recipient'=>['required','email'],'subject'=>['required','string','max:180'],'message'=>['required','string','max:5000']]);
        try {
            $mailService->send($record,$data['recipient'],$data['subject'],$data['message'],(int)$request->user()->id);
        } catch (\LogicException $exception) {
            return back()->withErrors(['email'=>$exception->getMessage()]);
        } catch (\Throwable $exception) {
            DB::table('reservation_emails')->insert(['reservation_id'=>$record->id,'sent_by'=>$request->user()->id,'recipient'=>$data['recipient'],'subject'=>$data['subject'],'message'=>$data['message'],'status'=>'failed','error'=>$exception->getMessage(),'created_at'=>now(),'updated_at'=>now()]);
            return back()->withErrors(['email'=>'The reservation email could not be sent: '.$exception->getMessage()]);
        }
        return back()->with('success','Reservation request emailed to '.$data['recipient'].'. The status is now Requested.');
    }

    public function emailAll(Request $request, int $quotation, ReservationMailService $mailService): RedirectResponse
    {
        $mailService->ensureForQuotation($quotation);
        $sent = 0; $missing = []; $locked = 0;
        foreach ($mailService->recordsForQuotation($quotation) as $record) {
            if ($record->reservation_mail_sent_at) { $locked++; continue; }
            if (!$record->supplier_email) { $missing[] = ($record->supplier ?: $record->service_title ?: 'Service #'.$record->id); continue; }
            try {
                $mailService->send($record,$record->supplier_email,$mailService->subject($record),$mailService->message($record),(int)$request->user()->id);
                $sent++;
            } catch (\LogicException) { $locked++; }
            catch (\Throwable $exception) { $missing[] = ($record->supplier ?: 'Service #'.$record->id).' ('.$exception->getMessage().')'; }
        }
        $message = "{$sent} reservation mail(s) sent and locked. {$locked} already sent.";
        if ($missing) $message .= ' Missing supplier email: '.implode(', ',array_unique($missing)).'.';
        return back()->with($missing?'warning':'success',$message);
    }
}
