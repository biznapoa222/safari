<?php

namespace App\Notifications;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WebsiteInquiryReceived extends Notification
{
    use Queueable;

    public function __construct(private readonly Lead $lead, private readonly bool $adminCopy = true)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        if (! $this->adminCopy) {
            return (new MailMessage)
                ->subject('We received your Shishi Footsteps safari inquiry')
                ->greeting('Hello '.$this->lead->name.',')
                ->line('Thank you for asking Shishi Footsteps to help plan your safari.')
                ->line('A safari specialist will review your preferences and contact you soon.')
                ->line('Preferred destination: '.($this->lead->destination ?: 'To be discussed'))
                ->line('Travel date: '.($this->lead->travel_date?->toFormattedDateString() ?: 'Flexible'))
                ->line('Travelers: '.$this->lead->travelers);
        }

        return (new MailMessage)
            ->subject('New website safari inquiry: '.$this->lead->name)
            ->line('A new website lead is ready in the CRM.')
            ->line('Name: '.$this->lead->name)
            ->line('Email: '.$this->lead->email)
            ->line('Phone: '.($this->lead->phone ?: 'Not provided'))
            ->line('Destination: '.($this->lead->destination ?: 'Not sure yet'))
            ->line('Travelers: '.$this->lead->travelers)
            ->action('Open Lead', route('admin.leads.show', $this->lead));
    }
}
