<?php

namespace App\Http\Controllers;

use App\Services\QuotationPricingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AccommodationController extends Controller
{
    public function index(Request $request): View
    {
        $hotels = DB::table('hotels')
            ->leftJoin('destinations', 'destinations.id', '=', 'hotels.destination_id')
            ->select('hotels.*', 'destinations.name as destination_name', 'destinations.country as country')
            ->when($request->filled('country'), fn ($query) => $query->where('destinations.country', $request->country))
            ->when($request->filled('search'), fn ($query) => $query->where(function ($query) use ($request) {
                $term = '%'.$request->search.'%';
                $query->where('hotels.name', 'like', $term)
                    ->orWhere('destinations.name', 'like', $term)
                    ->orWhere('destinations.country', 'like', $term);
            }))
            ->orderBy('destinations.country')
            ->orderBy('hotels.name')
            ->paginate(15)
            ->withQueryString();

        foreach ($hotels as $hotel) {
            $hotel->room_count = DB::table('room_types')->where('hotel_id', $hotel->id)->count();
            $hotel->rate_count = DB::table('hotel_rates')
                ->join('room_types', 'room_types.id', '=', 'hotel_rates.room_type_id')
                ->where('room_types.hotel_id', $hotel->id)->count();
            $hotel->translations = DB::table('content_items')
                ->join('content_translations', 'content_translations.content_item_id', '=', 'content_items.id')
                ->where('content_items.type', 'accommodation')
                ->where('content_items.name', $hotel->name)
                ->pluck('content_translations.language_code')->all();
            $hotel->destination_name = $hotel->destination_name ?: 'No destination selected';
            $hotel->country = $hotel->country ?: 'Unassigned';
        }

        return view('admin.accommodations.index', [
            'hotels' => $hotels,
            'countries' => DB::table('destinations')
                ->whereNotNull('country')
                ->distinct()
                ->orderBy('country')
                ->pluck('country'),
        ]);
    }

    public function create(): View
    {
        return view('admin.accommodations.form', [
            'hotel' => null,
            'destinations' => $this->destinationOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateHotel($request);
        $id = DB::table('hotels')->insertGetId([
            ...$data,
            'status' => $this->resolveHotelStatus($request),
            'created_at' => now(), 'updated_at' => now(),
        ]);

        return redirect()->route('admin.accommodations.edit', $id)->with('success', 'Accommodation created. Add its room types and seasonal rates below.');
    }

    public function edit(int $hotel): View
    {
        $record = DB::table('hotels')->find($hotel);
        abort_unless($record, 404);

        $rooms = DB::table('room_types')->where('hotel_id', $hotel)->orderBy('name')->get();
        foreach ($rooms as $room) {
            $room->rates = DB::table('hotel_rates')->where('room_type_id', $room->id)->orderBy('valid_from')->get();
        }

        return view('admin.accommodations.form', [
            'hotel' => $record,
            'rooms' => $rooms,
            'destinations' => $this->destinationOptions(),
        ]);
    }

    public function update(Request $request, int $hotel): RedirectResponse
    {
        abort_unless(DB::table('hotels')->where('id', $hotel)->exists(), 404);
        DB::table('hotels')->where('id', $hotel)->update([
            ...$this->validateHotel($request),
            'status' => $this->resolveHotelStatus($request),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Accommodation details updated.');
    }

    public function destroy(int $hotel): RedirectResponse
    {
        DB::table('hotels')->where('id', $hotel)->delete();

        return redirect()->route('admin.accommodations.index')->with('success', 'Accommodation deleted.');
    }

    public function compare(Request $request): View
    {
        $rates = DB::table('hotel_rates')
            ->join('room_types', 'room_types.id', '=', 'hotel_rates.room_type_id')
            ->join('hotels', 'hotels.id', '=', 'room_types.hotel_id')
            ->leftJoin('destinations', 'destinations.id', '=', 'hotels.destination_id')
            ->select('hotel_rates.*', 'room_types.name as room_name', 'room_types.max_adults', 'room_types.max_children', 'room_types.is_family_room', 'room_types.is_interconnecting', 'hotels.name as hotel_name', 'destinations.name as location', 'destinations.country', 'hotels.tier as supplier_type', 'hotels.description as notes')
            ->when($request->filled('location'), fn ($query) => $query->where('destinations.name', $request->location))
            ->when($request->filled('date'), fn ($query) => $query->whereDate('valid_from', '<=', $request->date)->whereDate('valid_to', '>=', $request->date))
            ->orderBy('hotel_rates.sell_rate')->get();

        return view('admin.accommodations.compare', [
            'rates' => $rates,
            'locations' => DB::table('destinations')->where('status', 1)->orderBy('name')->pluck('name'),
        ]);
    }

    public function storeRoom(Request $request, int $hotel): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'max_adults' => ['required', 'integer', 'min:1', 'max:12'],
            'max_children' => ['required', 'integer', 'min:0', 'max:12'],
            'baby_max_age' => ['nullable', 'integer', 'min:0', 'max:17'],
            'child_min_age' => ['nullable', 'integer', 'min:0', 'max:17'],
            'child_max_age' => ['nullable', 'integer', 'min:0', 'max:17'],
            'adult_min_age' => ['nullable', 'integer', 'min:1', 'max:30'],
            'inventory' => ['required', 'integer', 'min:1', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);
        $data['baby_max_age'] = $data['baby_max_age'] ?? 2;
        $data['child_min_age'] = $data['child_min_age'] ?? 3;
        $data['child_max_age'] = $data['child_max_age'] ?? 11;
        $data['adult_min_age'] = $data['adult_min_age'] ?? 12;
        $this->validateRoomAgeBands($data);
        DB::table('room_types')->insert([
            ...$data, 'hotel_id' => $hotel,
            'is_family_room' => $request->boolean('is_family_room'),
            'is_interconnecting' => $request->boolean('is_interconnecting'),
            'active' => true, 'created_at' => now(), 'updated_at' => now(),
        ]);

        return back()->with('success', 'Room type added.');
    }

    public function destroyRoom(int $hotel, int $room): RedirectResponse
    {
        DB::table('room_types')->where('hotel_id', $hotel)->where('id', $room)->delete();

        return back()->with('success', 'Room type deleted.');
    }

    public function storeRate(Request $request, int $hotel, int $room, QuotationPricingService $pricing): RedirectResponse
    {
        $data = $request->validate([
            'season_name' => ['required', 'string', 'max:120'],
            'valid_from' => ['required', 'date'],
            'valid_to' => ['required', 'date', 'after_or_equal:valid_from'],
            'meal_plan' => ['required', 'string', 'max:80'],
            'occupancy_basis' => ['required', 'in:per_room,per_person,per_adult,per_child'],
            'buy_rate' => ['required', 'numeric', 'min:0'],
            'markup_percent' => ['nullable', 'numeric', 'min:0', 'max:500'],
            'currency' => ['required', 'string', 'size:3'],
        ]);
        $defaultMarkup = DB::table('hotels')->where('id', $hotel)->value('default_markup_percent');
        $markup = (float) ($data['markup_percent'] ?? $defaultMarkup);
        DB::table('hotel_rates')->insert([
            ...$data, 'room_type_id' => $room, 'markup_percent' => $markup,
            'sell_rate' => $pricing->sellingPrice((float) $data['buy_rate'], $markup),
            'created_at' => now(), 'updated_at' => now(),
        ]);

        return back()->with('success', 'Seasonal room rate added and selling price calculated.');
    }

    public function destroyRate(int $hotel, int $room, int $rate): RedirectResponse
    {
        DB::table('hotel_rates')->where('room_type_id', $room)->where('id', $rate)->delete();

        return back()->with('success', 'Rate deleted.');
    }

    private function validateHotel(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:180'],
            'country' => ['nullable', 'string', 'max:120'],
            'location' => ['nullable', 'string', 'max:180'],
            'destination_id' => ['nullable', 'exists:destinations,id'],
            'supplier_type' => ['nullable', 'string', 'max:120'],
            'luxury_level' => ['nullable', 'string', 'max:120'],
            'star_rating' => ['nullable', 'integer', 'min:1', 'max:7'],
            'tier' => ['nullable', 'string', 'max:120'],
            'meal_plan' => ['nullable', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:5000'],
            'amenities' => ['nullable', 'string', 'max:5000'],
            'hero_image' => ['nullable', 'string', 'max:2048'],
            'gallery' => ['nullable', 'string', 'max:5000'],
            'website' => ['nullable', 'string', 'max:255'],
            'reservation_email' => ['nullable', 'email', 'max:180'],
            'gps' => ['nullable', 'string', 'max:255'],
            'currency' => ['required', 'string', 'size:3'],
            'default_markup_percent' => ['required', 'numeric', 'min:0', 'max:500'],
            'rates' => ['nullable', 'string', 'max:5000'],
            'published' => ['nullable'],
        ]);

        if (! empty($data['destination_id']) && (empty($data['country']) || empty($data['location']))) {
            $destination = DB::table('destinations')->find($data['destination_id']);
            if ($destination) {
                $data['country'] = $data['country'] ?? $destination->country;
                $data['location'] = $data['location'] ?? $destination->name;
            }
        }

        $data['country'] = $data['country'] ?: 'Unassigned';
        $data['location'] = $data['location'] ?: ($data['country'] ?: 'Unassigned');
        $data['supplier_type'] = $data['supplier_type'] ?? 'recommended';
        $data['luxury_level'] = $data['luxury_level'] ?? ($data['tier'] ?? 'standard');
        if (empty($data['tier']) && ! empty($data['supplier_type'])) {
            $data['tier'] = $data['supplier_type'];
        }
        $data['published'] = $request->boolean('published');

        return $data;
    }

    private function resolveHotelStatus(Request $request): string|int|bool
    {
        $status = $request->input('status', '1');
        if (in_array((string) $status, ['active', 'inactive'], true)) {
            return $status;
        }

        return $request->boolean('status') ? 1 : 0;
    }

    private function destinationOptions()
    {
        return DB::table('destinations')
            ->where('status', 1)
            ->orderBy('country')
            ->orderBy('name')
            ->get(['id', 'name', 'country']);
    }

    private function validateRoomAgeBands(array $data): void
    {
        if ((int) $data['baby_max_age'] >= (int) $data['child_min_age']) {
            throw ValidationException::withMessages(['child_min_age' => 'Child minimum age must be higher than baby maximum age.']);
        }

        if ((int) $data['child_min_age'] > (int) $data['child_max_age']) {
            throw ValidationException::withMessages(['child_max_age' => 'Child maximum age must be equal to or higher than child minimum age.']);
        }

        if ((int) $data['child_max_age'] >= (int) $data['adult_min_age']) {
            throw ValidationException::withMessages(['adult_min_age' => 'Adult minimum age must be higher than child maximum age.']);
        }
    }
}
