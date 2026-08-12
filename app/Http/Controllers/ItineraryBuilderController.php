<?php

namespace App\Http\Controllers;

use App\Models\ItineraryDayV2;
use App\Models\ItineraryV2;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ItineraryBuilderController extends Controller
{
    public function index(Request $request): View
    {
        $itineraries = ItineraryV2::when($request->filled('search'), fn($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->when($request->filled('country'), fn($q) => $q->where('country', $request->country))
            ->orderBy('created_at', 'desc')
            ->paginate(20)->withQueryString();

        return view('admin.itinerary-builder.index', compact('itineraries'));
    }

    public function create(): View
    {
        return view('admin.itinerary-builder.form', ['itinerary' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'duration_days' => 'required|integer|min:1',
            'country' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
            'price_from' => 'nullable|numeric|min:0',
            'currency' => 'required|string|size:3',
            'inclusions' => 'nullable|string',
            'exclusions' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(4);
        if (isset($data['inclusions'])) $data['inclusions'] = explode("\n", str_replace("\r", "", $data['inclusions']));
        if (isset($data['exclusions'])) $data['exclusions'] = explode("\n", str_replace("\r", "", $data['exclusions']));

        $itinerary = ItineraryV2::create($data);

        // Create placeholder days
        for ($i = 1; $i <= $data['duration_days']; $i++) {
            $itinerary->days()->create([
                'day_number' => $i,
                'title' => "Day {$i}",
                'sort_order' => $i,
            ]);
        }

        return redirect()->route('admin.itinerary-builder.edit', $itinerary)
            ->with('success', 'Itinerary created. Add day details.');
    }

    public function show(ItineraryV2 $itinerary): View
    {
        $itinerary->load('days');
        return view('admin.itinerary-builder.show', compact('itinerary'));
    }

    public function edit(ItineraryV2 $itinerary): View
    {
        $itinerary->load('days');
        return view('admin.itinerary-builder.form', compact('itinerary'));
    }

    public function update(Request $request, ItineraryV2 $itinerary): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'duration_days' => 'required|integer|min:1',
            'country' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
            'price_from' => 'nullable|numeric|min:0',
            'currency' => 'required|string|size:3',
            'inclusions' => 'nullable|string',
            'exclusions' => 'nullable|string',
            'notes' => 'nullable|string',
            'published' => 'boolean',
            'featured' => 'boolean',
        ]);

        $data['published'] = $request->boolean('published');
        $data['featured'] = $request->boolean('featured');
        if (isset($data['inclusions']) && is_string($data['inclusions'])) {
            $data['inclusions'] = array_filter(explode("\n", str_replace("\r", "", $data['inclusions'])));
        }
        if (isset($data['exclusions']) && is_string($data['exclusions'])) {
            $data['exclusions'] = array_filter(explode("\n", str_replace("\r", "", $data['exclusions'])));
        }

        $itinerary->update($data);

        return redirect()->route('admin.itinerary-builder.edit', $itinerary)
            ->with('success', 'Itinerary updated.');
    }

    public function destroy(ItineraryV2 $itinerary): RedirectResponse
    {
        $itinerary->delete();
        return redirect()->route('admin.itinerary-builder.index')->with('success', 'Itinerary deleted.');
    }

    public function storeDay(Request $request, ItineraryV2 $itinerary): RedirectResponse
    {
        $data = $request->validate([
            'day_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'accommodation_id' => 'nullable|exists:accommodations,id',
            'activities' => 'nullable|string',
            'meal_plan' => 'nullable|string|max:255',
            'transfers' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $data['itinerary_v2_id'] = $itinerary->id;
        $data['sort_order'] = $data['day_number'];

        ItineraryDayV2::updateOrCreate(
            ['itinerary_v2_id' => $itinerary->id, 'day_number' => $data['day_number']],
            $data
        );

        return back()->with('success', 'Day saved.');
    }

    public function updateDay(Request $request, ItineraryV2 $itinerary, ItineraryDayV2 $day): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'accommodation_id' => 'nullable|exists:accommodations,id',
            'activities' => 'nullable|string',
            'meal_plan' => 'nullable|string|max:255',
            'transfers' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $day->update($data);

        return back()->with('success', 'Day updated.');
    }

    public function destroyDay(ItineraryV2 $itinerary, ItineraryDayV2 $day): RedirectResponse
    {
        $day->delete();
        return back()->with('success', 'Day removed.');
    }

    public function reorderDays(Request $request, ItineraryV2 $itinerary): RedirectResponse
    {
        $request->validate(['days' => 'required|array']);
        foreach ($request->days as $order) {
            ItineraryDayV2::where('itinerary_v2_id', $itinerary->id)
                ->where('id', $order['id'])
                ->update(['sort_order' => $order['sort_order'], 'day_number' => $order['sort_order']]);
        }
        return back()->with('success', 'Days reordered.');
    }

    public function togglePublish(ItineraryV2 $itinerary): RedirectResponse
    {
        $itinerary->update(['published' => !$itinerary->published]);
        return back()->with('success', $itinerary->published ? 'Published.' : 'Unpublished.');
    }
}
