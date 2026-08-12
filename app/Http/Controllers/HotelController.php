<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Destination;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HotelController extends Controller
{
    public function index(): View
    {
        $hotels = Hotel::with('destination')->orderBy('name')->paginate(20);
        return view('admin.hotels.index', compact('hotels'));
    }

    public function create(): View
    {
        $destinations = Destination::where('status', 1)->orderBy('name')->get();
        return view('admin.hotels.create', compact('destinations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'destination_id' => 'nullable|exists:destinations,id',
            'star_rating' => 'nullable|integer|min:1|max:7',
            'tier' => 'nullable|string|max:255',
            'meal_plan' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        Hotel::create($validated);

        return redirect()->route('admin.hotels.index')
            ->with('success', 'Hotel created successfully.');
    }

    public function show(Hotel $hotel): View
    {
        return view('admin.hotels.show', compact('hotel'));
    }

    public function edit(Hotel $hotel): View
    {
        $destinations = Destination::where('status', 1)->orderBy('name')->get();
        return view('admin.hotels.edit', compact('hotel', 'destinations'));
    }

    public function update(Request $request, Hotel $hotel): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'destination_id' => 'nullable|exists:destinations,id',
            'star_rating' => 'nullable|integer|min:1|max:7',
            'tier' => 'nullable|string|max:255',
            'meal_plan' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        $hotel->update($validated);

        return redirect()->route('admin.hotels.index')
            ->with('success', 'Hotel updated successfully.');
    }

    public function destroy(Hotel $hotel): RedirectResponse
    {
        $hotel->delete();
        return redirect()->route('admin.hotels.index')
            ->with('success', 'Hotel deleted successfully.');
    }
}
