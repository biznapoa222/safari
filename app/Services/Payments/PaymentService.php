<?php

namespace App\Services\Payments;

use App\Models\Payment;
use App\Models\PaymentLink;
use Illuminate\Support\Str;

class PaymentService
{
    private array $gateways = [];

    public function __construct()
    {
        $this->gateways['manual'] = app(ManualGateway::class);
        if (config('services.stripe.secret_key')) {
            $this->gateways['stripe'] = app(StripeGateway::class);
        }
        if (config('services.flutterwave.secret_key')) {
            $this->gateways['flutterwave'] = app(FlutterwaveGateway::class);
        }
    }

    public function availableGateways(): array
    {
        return array_keys($this->gateways);
    }

    public function gateway(?string $name = null): PaymentGateway
    {
        $name ??= config('services.payments.default_gateway', 'stripe');

        if (!isset($this->gateways[$name])) {
            throw new \RuntimeException("Payment gateway [{$name}] is not configured.");
        }

        return $this->gateways[$name];
    }

    public function initiatePayment(PaymentLink $link, string $gateway, string $successUrl, string $cancelUrl, array $customer = []): array
    {
        $driver = $this->gateway($gateway);

        $result = $driver->createCheckout([
            'amount' => $link->amount,
            'currency' => $link->currency,
            'description' => ucfirst($link->type) . ' payment for booking #' . $link->booking_id,
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'booking_id' => $link->booking_id,
            'token' => $link->token,
            'reference' => 'LINK-' . strtoupper(Str::random(10)),
            'email' => $customer['email'] ?? 'guest@safariflow.com',
            'customer_name' => $customer['name'] ?? 'Guest',
        ]);

        Payment::create([
            'booking_id' => $link->booking_id,
            'reference' => $result['session_id'],
            'amount' => $link->amount,
            'currency' => $link->currency,
            'method' => $gateway === 'stripe' ? 'credit_card' : $gateway,
            'gateway' => $gateway,
            'gateway_session_id' => $result['session_id'],
            'status' => 'pending',
            'type' => $link->type,
        ]);

        $link->update(['gateway' => $gateway]);

        return $result;
    }

    public function completePayment(Payment $payment, string $gatewaySessionId): Payment
    {
        $driver = $this->gateway($payment->gateway);
        $verification = $driver->verifyPayment($gatewaySessionId);

        $payment->update([
            'gateway_response' => $verification['gateway_response'],
            'status' => $verification['verified'] ? 'completed' : 'failed',
            'paid_at' => $verification['verified'] ? now() : null,
        ]);

        if ($verification['verified']) {
            $booking = $payment->booking;
            $totalPaid = $booking->payments()->where('status', 'completed')->sum('amount');
            $booking->update([
                'amount_paid' => $totalPaid,
                'balance' => max(0, $booking->total_amount - $totalPaid),
                'payment_status' => $totalPaid >= $booking->total_amount ? 'paid' : 'partial',
            ]);

            $link = PaymentLink::where('booking_id', $payment->booking_id)
                ->where('is_used', false)
                ->latest()
                ->first();
            if ($link) {
                $link->update(['is_used' => true, 'used_at' => now()]);
            }
        }

        return $payment->fresh();
    }
}
