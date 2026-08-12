<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class KpiAlertNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $metric,
        public string $value,
        public string $threshold,
        public string $direction,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("[Shishi Footsteps KPI] Alert: {$this->metric}")
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("KPI Alert: {$this->metric}")
            ->line("Current value: {$this->value}")
            ->line("Threshold: {$this->threshold} ({$this->direction})")
            ->action('View KPI Dashboard', url('/admin/reports/kpi'))
            ->line('This is an automated KPI alert.');
    }
}
