<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomingMailAccount extends Model
{
    protected $table = 'incoming_mail_accounts';

    protected $fillable = [
        'label', 'protocol', 'host', 'port', 'encryption',
        'username', 'password', 'folder', 'is_active', 'mark_seen',
        'delete_after_fetch', 'last_fetched_at', 'last_uid',
        'auto_create_request', 'assigned_consultant_id', 'error',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'mark_seen' => 'boolean',
        'delete_after_fetch' => 'boolean',
        'auto_create_request' => 'boolean',
        'last_fetched_at' => 'datetime',
        'last_uid' => 'integer',
        'port' => 'integer',
    ];

    protected $hidden = ['password'];

    public function consultant()
    {
        return $this->belongsTo(User::class, 'assigned_consultant_id');
    }

    public function emails()
    {
        return $this->hasMany(IncomingEmail::class, 'account_id');
    }
}
