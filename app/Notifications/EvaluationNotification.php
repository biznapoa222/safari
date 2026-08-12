<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EvaluationNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $type,
        public string $message,
        public int $quotationId,
        public string $severity = 'info',
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subject = match ($this->type) {
            'missing_invoice' => 'Missing Supplier Invoice - Action Required',
            'variance_detected' => 'Invoice Variance Detected',
            'invoice_approved' => 'Invoice Approved for Payment',
            'payment_due' => 'Payment Deadline Approaching',
            'invoice_amended' => 'Invoice Requires Amendment',
            'duplicate_found' => 'Duplicate Invoice Detected',
            'evaluation_completed' => 'Evaluation Completed - Ready for Review',
            default => 'Evaluation Notification',
        };

        return (new MailMessage)
            ->subject("[Shishi Footsteps ERP] {$subject}")
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line($this->message)
            ->action('Open Evaluation', url("/admin/evaluations/{$this->quotationId}"))
            ->line('Severity: ' . ucfirst($this->severity))
            ->line('This is an automated notification from the Shishi Footsteps Evaluation System.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => $this->type,
            'severity' => $this->severity,
            'message' => $this->message,
            'quotation_id' => $this->quotationId,
        ];
    }
}
