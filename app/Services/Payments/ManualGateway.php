<?php

namespace App\Services\Payments;

class ManualGateway implements PaymentGateway
{
    public function name(): string
    {
        return 'manual';
    }

    public function createCheckout(array $params): array
    {
        return [
            'session_id' => 'MANUAL-' . strtoupper(uniqid()),
            'redirect_url' => $params['success_url'],
            'gateway' => $this->name(),
        ];
    }

    public function verifyPayment(string $sessionId): array
    {
        return [
            'verified' => true,
            'status' => 'completed',
            'payment_status' => 'paid',
            'amount_total' => 0,
            'currency' => 'USD',
            'gateway_response' => ['method' => 'manual', 'note' => 'Offline / manual payment'],
        ];
    }

    public function supportsRefunds(): bool
    {
        return false;
    }
}
