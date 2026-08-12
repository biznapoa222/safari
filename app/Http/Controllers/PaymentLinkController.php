<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\PaymentLink;
use App\Services\Payments\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PaymentLinkController extends Controller
{
    public function __construct(
        private PaymentService $paymentService
    ) {}

    public function store(Request $request, Booking $booking): RedirectResponse
    {
        $data = $request->validate([
            'type' => 'required|in:payment,deposit,balance',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $data['token'] = Str::random(64);
        $data['booking_id'] = $booking->id;

        PaymentLink::create($data);

        return back()->with('success', 'Payment link generated. Share URL: ' . route('admin.payments.links.show', $data['token']));
    }

    public function show(string $token): View
    {
        $link = PaymentLink::with('booking.lead')->where('token', $token)->firstOrFail();

        if ($link->is_used || ($link->expires_at && $link->expires_at->isPast())) {
            return view('public.payment-link.expired', compact('link'));
        }

        $gateways = $this->paymentService->availableGateways();

        return view('public.payment-link.show', compact('link', 'gateways'));
    }

    public function pay(Request $request, string $token): RedirectResponse
    {
        $link = PaymentLink::with('booking.lead')->where('token', $token)->firstOrFail();

        if ($link->is_used) {
            return back()->with('error', 'This payment link has already been used.');
        }

        if ($link->expires_at && $link->expires_at->isPast()) {
            return back()->with('error', 'This payment link has expired.');
        }

        $allowed = implode(',', $this->paymentService->availableGateways());
        $validated = $request->validate([
            'gateway' => "required|in:{$allowed}",
            'accept_cancellation' => 'required|accepted',
        ]);

        $successUrl = route('payments.callback', ['gateway' => $validated['gateway']]);
        if ($validated['gateway'] !== 'manual') {
            $successUrl .= '?session_id={CHECKOUT_SESSION_ID}&token=' . $link->token;
        } else {
            $successUrl .= '?session_id=MANUAL&token=' . $link->token;
        }
        $cancelUrl = route('admin.payments.links.show', $link->token);

        $customer = [
            'email' => $link->booking->lead?->email ?? 'guest@safariflow.com',
            'name' => $link->booking->lead?->name ?? 'Guest',
        ];

        try {
            $result = $this->paymentService->initiatePayment(
                $link,
                $validated['gateway'],
                $successUrl,
                $cancelUrl,
                $customer
            );

            return redirect()->away($result['redirect_url']);
        } catch (\Exception $e) {
            logger()->error('Payment initiation failed', ['error' => $e->getMessage(), 'token' => $token]);
            return back()->with('error', 'Unable to process payment. Please try again later.');
        }
    }
}
