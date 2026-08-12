<?php

namespace App\Services\Payments;

use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\StripeClient;

class StripeGateway implements PaymentGateway
{
    private StripeClient $stripe;

    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret_key'));
        $this->stripe = new StripeClient(config('services.stripe.secret_key'));
    }

    public function name(): string
    {
        return 'stripe';
    }

    public function createCheckout(array $params): array
    {
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => strtolower($params['currency']),
                    'product_data' => ['name' => $params['description'] ?? 'Safari Payment'],
                    'unit_amount' => (int) round($params['amount'] * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $params['success_url'],
            'cancel_url' => $params['cancel_url'],
            'metadata' => [
                'booking_id' => $params['booking_id'] ?? '',
                'payment_link_token' => $params['token'] ?? '',
            ],
        ]);

        return [
            'session_id' => $session->id,
            'redirect_url' => $session->url,
            'gateway' => $this->name(),
        ];
    }

    public function verifyPayment(string $sessionId): array
    {
        $session = $this->stripe->checkout->sessions->retrieve($sessionId);

        return [
            'verified' => $session->payment_status === 'paid',
            'status' => $session->status,
            'payment_status' => $session->payment_status,
            'amount_total' => $session->amount_total / 100,
            'currency' => strtoupper($session->currency),
            'gateway_response' => $session->toArray(),
        ];
    }

    public function supportsRefunds(): bool
    {
        return true;
    }
}
