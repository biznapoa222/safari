<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $bookings = Booking::with('lead', 'consultant')
            ->when($request->filled('search'), fn($q) => $q->where('reference', 'like', "%{$request->search}%")
                ->orWhereHas('lead', fn($q) => $q->where('name', 'like', "%{$request->search}%")))
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('payment_status'), fn($q) => $q->where('payment_status', $request->payment_status))
            ->latest()
            ->paginate(20)->withQueryString();

        return view('admin.bookings.index', [
            'bookings' => $bookings,
            'statuses' => Booking::$statuses,
        ]);
    }

    public function create(Request $request): View
    {
        $lead = null;
        if ($request->filled('lead')) {
            $lead = Lead::find($request->lead);
        }

        return view('admin.bookings.form', [
            'booking' => null,
            'lead' => $lead,
            'leads' => Lead::whereNotIn('status', ['lost'])->orderBy('name')->get(),
            'consultants' => User::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'lead_id' => 'nullable|exists:leads,id',
            'status' => 'required|in:' . implode(',', array_keys(Booking::$statuses)),
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'guests' => 'required|integer|min:1',
            'currency' => 'required|string|size:3',
            'assigned_consultant_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        $data['reference'] = 'BK-' . strtoupper(Str::random(8));

        $booking = Booking::create($data);

        return redirect()->route('admin.bookings.edit', $booking)
            ->with('success', 'Booking created. Add items and manage payments.');
    }

    public function show(Booking $booking): View
    {
        $booking->load(['lead', 'consultant', 'items', 'payments', 'paymentLinks']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking): View
    {
        $booking->load(['lead', 'items', 'payments', 'paymentLinks']);
        return view('admin.bookings.form', [
            'booking' => $booking,
            'leads' => Lead::whereNotIn('status', ['lost'])->orderBy('name')->get(),
            'consultants' => User::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $data = $request->validate([
            'lead_id' => 'nullable|exists:leads,id',
            'status' => 'required|in:' . implode(',', array_keys(Booking::$statuses)),
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'guests' => 'required|integer|min:1',
            'total_amount' => 'nullable|numeric|min:0',
            'amount_paid' => 'nullable|numeric|min:0',
            'currency' => 'required|string|size:3',
            'payment_status' => 'required|in:unpaid,partial,paid',
            'assigned_consultant_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
            'cancellation_policy_accepted' => 'boolean',
        ]);

        $data['cancellation_policy_accepted'] = $request->boolean('cancellation_policy_accepted');
        if ($data['cancellation_policy_accepted'] && !$booking->cancellation_accepted_at) {
            $data['cancellation_accepted_at'] = now();
        }

        $data['balance'] = ($data['total_amount'] ?? 0) - ($data['amount_paid'] ?? 0);
        $booking->update($data);

        return back()->with('success', 'Booking updated.');
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        $booking->delete();
        return redirect()->route('admin.bookings.index')->with('success', 'Booking deleted.');
    }
}
