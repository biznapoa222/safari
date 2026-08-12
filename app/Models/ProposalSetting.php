<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalSetting extends Model
{
    protected $table = 'proposal_settings';

    protected $fillable = [
        'company_name', 'logo', 'company_profile', 'awards', 'certifications',
        'testimonials', 'contact_email', 'contact_phone', 'website', 'social_links',
        'booking_terms', 'payment_schedule', 'cancellation_policy', 'refund_policy', 'important_notes',
    ];

    protected function casts(): array
    {
        return ['social_links' => 'array'];
    }
}
