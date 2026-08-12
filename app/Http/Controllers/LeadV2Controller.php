<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadV2Controller extends Controller
{
    public function index(Request $request): View
    {
        $leads = Lead::with('consultant')
            ->when($request->filled('search'), fn($q) => $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%")
                ->orWhere('phone', 'like', "%{$request->search}%"))
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('source'), fn($q) => $q->where('source', $request->source))
            ->when($request->filled('consultant'), fn($q) => $q->where('assigned_consultant_id', $request->consultant))
            ->latest()
            ->paginate(20)->withQueryString();

        return view('admin.leads.v2.index', [
            'leads' => $leads,
            'statuses' => Lead::$statuses,
            'sources' => Lead::$sources,
            'consultants' => User::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function show(Lead $lead): View
    {
        $lead->load(['consultant', 'conversations.user', 'bookings']);
        return view('admin.leads.v2.show', compact('lead'));
    }

    public function update(Request $request, Lead $lead): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:100',
            'source' => 'required|in:' . implode(',', array_keys(Lead::$sources)),
            'status' => 'required|in:' . implode(',', array_keys(Lead::$statuses)),
            'notes' => 'nullable|string',
            'estimated_value' => 'nullable|numeric|min:0',
            'currency' => 'required|string|size:3',
            'travel_date' => 'nullable|date',
            'travelers' => 'nullable|integer|min:1',
            'destination' => 'nullable|string|max:255',
            'interests' => 'nullable|string',
        ]);

        if ($data['status'] === 'confirmed' && !$lead->booking_at) {
            $data['booking_at'] = now();
        }

        $lead->update($data);

        return back()->with('success', 'Lead updated.');
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $lead->delete();
        return redirect()->route('admin.leads.index')->with('success', 'Lead deleted.');
    }

    public function convert(Lead $lead): RedirectResponse
    {
        $lead->update([
            'status' => 'confirmed',
            'booking_at' => now(),
        ]);

        return redirect()->route('admin.bookings.create', ['lead' => $lead->id])
            ->with('success', 'Lead converted. Create a booking.');
    }

    public function assign(Request $request, Lead $lead): RedirectResponse
    {
        $data = $request->validate([
            'assigned_consultant_id' => 'required|exists:users,id',
        ]);

        $lead->update($data);

        return back()->with('success', 'Lead assigned.');
    }

    public function conversations(Lead $lead)
    {
        $lead->load('conversations.user');
        return view('admin.leads.v2.conversations', compact('lead'));
    }
}
