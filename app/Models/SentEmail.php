<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SentEmail extends Model
{
    protected $table = 'sent_emails';

    protected $fillable = [
        'category', 'subject', 'to_email', 'to_name',
        'from_email', 'from_name', 'related_type', 'related_id',
        'sent_by', 'body', 'status', 'error', 'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];
}
