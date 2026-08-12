<?php

namespace App\Services\Payments;

interface PaymentGateway
{
    public function name(): string;

    public function createCheckout(array $params): array;

    public function verifyPayment(string $sessionId): array;

    public function supportsRefunds(): bool;
}
