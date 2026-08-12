<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function store(Request $request, Booking $booking): RedirectResponse
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3',
            'method' => 'required|in:' . implode(',', array_keys(Payment::$methods)),
            'type' => 'required|in:payment,deposit,balance',
            'paid_at' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $data['reference'] = 'PAY-' . strtoupper(Str::random(10));
        $data['status'] = 'completed';
        $data['booking_id'] = $booking->id;

        Payment::create($data);

        // Update booking amounts
        $totalPaid = $booking->payments()->where('status', 'completed')->sum('amount');
        $booking->update([
            'amount_paid' => $totalPaid,
            'balance' => max(0, $booking->total_amount - $totalPaid),
            'payment_status' => $totalPaid >= $booking->total_amount ? 'paid' : ($totalPaid > 0 ? 'partial' : 'unpaid'),
        ]);

        return back()->with('success', 'Payment recorded.');
    }

    public function destroy(Payment $payment): RedirectResponse
    {
        $booking = $payment->booking;
        $payment->delete();

        $totalPaid = $booking->payments()->where('status', 'completed')->sum('amount');
        $booking->update([
            'amount_paid' => $totalPaid,
            'balance' => max(0, $booking->total_amount - $totalPaid),
            'payment_status' => $totalPaid >= $booking->total_amount ? 'paid' : ($totalPaid > 0 ? 'partial' : 'unpaid'),
        ]);

        return back()->with('success', 'Payment deleted.');
    }
}
