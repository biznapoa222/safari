<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DestinationController extends Controller
{
    public function index(): View
    {
        $destinations = Destination::orderBy('name')->paginate(20);
        return view('admin.destinations.index', compact('destinations'));
    }

    public function create(): View
    {
        return view('admin.destinations.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'highlights' => 'nullable|string',
            'wildlife' => 'nullable|string',
            'climate' => 'nullable|string',
            'best_time_to_visit' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        Destination::create($validated);

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destination created successfully.');
    }

    public function show(Destination $destination): View
    {
        return view('admin.destinations.show', compact('destination'));
    }

    public function edit(Destination $destination): View
    {
        return view('admin.destinations.edit', compact('destination'));
    }

    public function update(Request $request, Destination $destination): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'highlights' => 'nullable|string',
            'wildlife' => 'nullable|string',
            'climate' => 'nullable|string',
            'best_time_to_visit' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        $destination->update($validated);

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destination updated successfully.');
    }

    public function destroy(Destination $destination): RedirectResponse
    {
        $destination->delete();
        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destination deleted successfully.');
    }
}
