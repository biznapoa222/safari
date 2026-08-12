<?php

namespace App\Services\Payments;

use Illuminate\Support\Facades\Http;

class FlutterwaveGateway implements PaymentGateway
{
    private string $secretKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->secretKey = config('services.flutterwave.secret_key');
        $this->baseUrl = config('services.flutterwave.base_url', 'https://api.flutterwave.com/v3');
    }

    public function name(): string
    {
        return 'flutterwave';
    }

    public function createCheckout(array $params): array
    {
        $payload = [
            'tx_ref' => $params['reference'] ?? uniqid('safari_'),
            'amount' => $params['amount'],
            'currency' => $params['currency'],
            'redirect_url' => $params['success_url'],
            'meta' => [
                'booking_id' => $params['booking_id'] ?? '',
                'payment_link_token' => $params['token'] ?? '',
            ],
            'customer' => [
                'email' => $params['email'] ?? 'guest@safariflow.com',
                'name' => $params['customer_name'] ?? 'Guest',
            ],
            'customizations' => [
                'title' => $params['description'] ?? 'Safari Payment',
                'description' => $params['description'] ?? '',
            ],
        ];

        $response = Http::withToken($this->secretKey)
            ->post("{$this->baseUrl}/payments", $payload)
            ->throw()
            ->json();

        return [
            'session_id' => $response['data']['id'] ?? null,
            'redirect_url' => $response['data']['link'] ?? null,
            'gateway' => $this->name(),
        ];
    }

    public function verifyPayment(string $transactionId): array
    {
        $response = Http::withToken($this->secretKey)
            ->get("{$this->baseUrl}/transactions/{$transactionId}/verify")
            ->throw()
            ->json();

        $data = $response['data'] ?? [];

        return [
            'verified' => ($data['status'] ?? '') === 'successful',
            'status' => $data['status'] ?? 'unknown',
            'payment_status' => $data['status'] === 'successful' ? 'paid' : 'failed',
            'amount_total' => $data['amount'] ?? 0,
            'currency' => $data['currency'] ?? $params['currency'] ?? 'USD',
            'gateway_response' => $data,
        ];
    }

    public function supportsRefunds(): bool
    {
        return true;
    }
}
