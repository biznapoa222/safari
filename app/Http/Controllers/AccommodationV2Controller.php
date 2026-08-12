<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use App\Models\AccommodationRate;
use App\Models\AccommodationRoom;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AccommodationV2Controller extends Controller
{
    public function index(Request $request): View
    {
        $accommodations = Accommodation::withCount('rooms')
            ->when($request->filled('search'), fn($q) => $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('location', 'like', "%{$request->search}%"))
            ->when($request->filled('country'), fn($q) => $q->where('country', $request->country))
            ->when($request->filled('type'), fn($q) => $q->where('type', $request->type))
            ->orderBy('country')->orderBy('name')
            ->paginate(20)->withQueryString();

        return view('admin.accommodations-v2.index', [
            'accommodations' => $accommodations,
            'countries' => Accommodation::distinct()->orderBy('country')->pluck('country'),
            'types' => Accommodation::$types,
        ]);
    }

    public function create(): View
    {
        return view('admin.accommodations-v2.form', ['accommodation' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:' . implode(',', array_keys(Accommodation::$types)),
            'country' => 'required|string|max:100',
            'region' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'luxury_level' => 'nullable|in:luxury,premium,mid_range,budget',
            'currency' => 'required|string|size:3',
        ]);

        $accommodation = Accommodation::create($data);

        return redirect()->route('admin.accommodations-v2.edit', $accommodation)
            ->with('success', 'Accommodation created. Add room types below.');
    }

    public function edit(Accommodation $accommodation): View
    {
        $accommodation->load(['rooms.rates']);
        return view('admin.accommodations-v2.form', compact('accommodation'));
    }

    public function update(Request $request, Accommodation $accommodation): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:' . implode(',', array_keys(Accommodation::$types)),
            'country' => 'required|string|max:100',
            'region' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'luxury_level' => 'nullable|in:luxury,premium,mid_range,budget',
            'currency' => 'required|string|size:3',
            'published' => 'boolean',
            'featured' => 'boolean',
            'status' => 'required|in:active,inactive',
        ]);

        $data['published'] = $request->boolean('published');
        $data['featured'] = $request->boolean('featured');

        $accommodation->update($data);

        return redirect()->route('admin.accommodations-v2.edit', $accommodation)
            ->with('success', 'Accommodation updated.');
    }

    public function destroy(Accommodation $accommodation): RedirectResponse
    {
        $accommodation->delete();
        return redirect()->route('admin.accommodations-v2.index')->with('success', 'Accommodation deleted.');
    }

    public function togglePublish(Accommodation $accommodation): RedirectResponse
    {
        $accommodation->update(['published' => !$accommodation->published]);
        return back()->with('success', $accommodation->published ? 'Published.' : 'Unpublished.');
    }

    public function storeRoom(Request $request, Accommodation $accommodation): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'max_adults' => 'required|integer|min:1',
            'max_children' => 'required|integer|min:0',
            'baby_max_age' => 'required|integer|min:0|max:17',
            'child_min_age' => 'required|integer|min:0|max:17',
            'child_max_age' => 'required|integer|min:0|max:17',
            'adult_min_age' => 'required|integer|min:1|max:30',
            'child_policy' => 'nullable|string',
            'inventory' => 'required|integer|min:1',
        ]);
        $this->validateRoomAgeBands($data);

        $accommodation->rooms()->create($data);

        return back()->with('success', 'Room type added.');
    }

    public function destroyRoom(Accommodation $accommodation, AccommodationRoom $room): RedirectResponse
    {
        $room->delete();
        return back()->with('success', 'Room type deleted.');
    }

    public function storeRate(Request $request, Accommodation $accommodation, AccommodationRoom $room): RedirectResponse
    {
        $data = $request->validate([
            'season_name' => 'required|string|max:255',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after_or_equal:valid_from',
            'meal_plan' => 'required|string|max:100',
            'rate' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'notes' => 'nullable|string',
        ]);

        $room->rates()->create($data);

        return back()->with('success', 'Rate added.');
    }

    public function destroyRate(Accommodation $accommodation, AccommodationRoom $room, AccommodationRate $rate): RedirectResponse
    {
        $rate->delete();
        return back()->with('success', 'Rate deleted.');
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
