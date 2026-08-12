<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomingEmail extends Model
{
    protected $table = 'incoming_emails';

    protected $fillable = [
        'account_id', 'message_id', 'uid', 'from_email', 'from_name',
        'to_email', 'subject', 'body_text', 'body_html', 'headers',
        'received_at', 'status', 'notes', 'lead_id', 'request_id',
        'assigned_to', 'error',
    ];

    protected $casts = [
        'received_at' => 'datetime',
        'headers' => 'array',
        'uid' => 'integer',
    ];

    public function account()
    {
        return $this->belongsTo(IncomingMailAccount::class, 'account_id');
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function request()
    {
        return $this->belongsTo(Request::class, 'request_id');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function isConvertable(): bool
    {
        return in_array($this->status, ['new', 'failed'], true);
    }
}
