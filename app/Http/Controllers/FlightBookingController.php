<?php

namespace App\Http\Controllers;

use App\Services\QuotationPricingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FlightBookingController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.flights.index', [
            'flights' => DB::table('flight_bookings')
                ->leftJoin('clients', 'clients.id', '=', 'flight_bookings.client_id')
                ->select('flight_bookings.*', 'clients.name as client_name')
                ->when($request->filled('type'), fn ($query) => $query->where('flight_type', $request->type))
                ->when($request->filled('status'), fn ($query) => $query->where('booking_status', $request->status))
                ->when($request->filled('search'), fn ($query) => $query->where(function ($query) use ($request) {
                    $query->where('passenger_name', 'like', '%'.$request->search.'%')
                        ->orWhere('pnr', 'like', '%'.$request->search.'%')
                        ->orWhere('ticket_number', 'like', '%'.$request->search.'%')
                        ->orWhere('flight_number', 'like', '%'.$request->search.'%');
                }))
                ->orderBy('departure_at')->paginate(25)->withQueryString(),
            'clients' => DB::table('clients')->orderBy('name')->get(),
            'editing' => $request->filled('edit') ? DB::table('flight_bookings')->find($request->integer('edit')) : null,
        ]);
    }

    public function store(Request $request, QuotationPricingService $pricing): RedirectResponse
    {
        $data = $this->validated($request);
        $selling = $pricing->sellingPrice((float) $data['base_fare'] + (float) $data['taxes'], (float) $data['markup_percent']);
        DB::table('flight_bookings')->insert([
            ...$data, 'request_reference' => 'FLT-'.now()->format('ymd').'-'.str_pad((string) (DB::table('flight_bookings')->count() + 1), 3, '0', STR_PAD_LEFT),
            'selling_total' => $selling, 'created_at' => now(), 'updated_at' => now(),
        ]);

        return back()->with('success', 'Flight request created and selling fare calculated.');
    }

    public function update(Request $request, int $flight, QuotationPricingService $pricing): RedirectResponse
    {
        $data = $this->validated($request);
        DB::table('flight_bookings')->where('id', $flight)->update([
            ...$data,
            'selling_total' => $pricing->sellingPrice((float) $data['base_fare'] + (float) $data['taxes'], (float) $data['markup_percent']),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.flights.index')->with('success', 'Flight booking updated.');
    }

    public function destroy(int $flight): RedirectResponse
    {
        DB::table('flight_bookings')->where('id', $flight)->delete();

        return back()->with('success', 'Flight booking deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'client_id' => ['nullable', 'exists:clients,id'],
            'passenger_name' => ['required', 'string', 'max:180'],
            'passenger_type' => ['required', 'in:adult,child,infant'],
            'passport_number' => ['nullable', 'string', 'max:80'],
            'airline' => ['required', 'string', 'max:120'],
            'flight_number' => ['required', 'string', 'max:20'],
            'flight_type' => ['required', 'in:domestic,international,charter'],
            'cabin_class' => ['required', 'in:economy,premium_economy,business,first'],
            'origin_code' => ['required', 'string', 'size:3'],
            'destination_code' => ['required', 'string', 'size:3', 'different:origin_code'],
            'departure_at' => ['required', 'date'],
            'arrival_at' => ['required', 'date', 'after:departure_at'],
            'pnr' => ['nullable', 'string', 'max:20'],
            'ticket_number' => ['nullable', 'string', 'max:40'],
            'baggage_allowance' => ['nullable', 'string', 'max:80'],
            'supplier' => ['nullable', 'string', 'max:120'],
            'base_fare' => ['required', 'numeric', 'min:0'],
            'taxes' => ['required', 'numeric', 'min:0'],
            'markup_percent' => ['required', 'numeric', 'min:0', 'max:500'],
            'currency' => ['required', 'string', 'size:3'],
            'payment_deadline' => ['nullable', 'date'],
            'payment_status' => ['required', 'in:unpaid,part_paid,paid,refunded'],
            'booking_status' => ['required', 'in:requested,on_hold,confirmed,ticketed,cancelled,completed'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
    }
}
