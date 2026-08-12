<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentLink;
use App\Services\Payments\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentWebhookController extends Controller
{
    public function __construct(
        private PaymentService $paymentService
    ) {}

    public function callback(Request $request, string $gateway): \Illuminate\Http\RedirectResponse
    {
        $sessionId = $request->query('session_id');
        $token = $request->query('token');

        if (!$sessionId || !$token) {
            return redirect()->route('home')->with('error', 'Invalid payment callback.');
        }

        $link = PaymentLink::where('token', $token)->first();

        if (!$link) {
            return redirect()->route('home')->with('error', 'Payment link not found.');
        }

        $payment = Payment::where('gateway_session_id', $sessionId)->first();

        if (!$payment) {
            return redirect()->route('home')->with('error', 'Payment record not found.');
        }

        try {
            $this->paymentService->completePayment($payment, $sessionId);
        } catch (\Exception $e) {
            logger()->error('Payment completion failed', ['session_id' => $sessionId, 'error' => $e->getMessage()]);
        }

        if ($payment->fresh()->status === 'completed') {
            return redirect()->route('home')->with('success', 'Payment completed successfully. Thank you!');
        }

        return redirect()->route('admin.payments.links.show', $token)->with('error', 'Payment verification failed. Please contact support.');
    }

    public function webhookStripe(Request $request): JsonResponse
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        if ($endpointSecret) {
            try {
                $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Invalid signature'], 400);
            }
        } else {
            $event = json_decode($payload);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $payment = Payment::where('gateway_session_id', $session->id)->first();

            if ($payment && $payment->status === 'pending') {
                $this->paymentService->completePayment($payment, $session->id);
            }
        }

        return response()->json(['received' => true]);
    }

    public function webhookFlutterwave(Request $request): JsonResponse
    {
        $payload = $request->all();
        $secretHash = config('services.flutterwave.webhook_secret');

        $signature = $request->header('verif-hash');
        if ($secretHash && $signature !== $secretHash) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if (($payload['event'] ?? '') === 'charge.completed' && ($payload['data']['status'] ?? '') === 'successful') {
            $transactionId = $payload['data']['id'] ?? null;
            if ($transactionId) {
                $payment = Payment::where('gateway_session_id', (string) $transactionId)->first();
                if ($payment && $payment->status === 'pending') {
                    $this->paymentService->completePayment($payment, (string) $transactionId);
                }
            }
        }

        return response()->json(['received' => true]);
    }
}
