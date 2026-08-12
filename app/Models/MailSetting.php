<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailSetting extends Model
{
    protected $table = 'mail_settings';

    protected $fillable = [
        'mailer', 'host', 'port', 'encryption',
        'username', 'password', 'from_address', 'from_name',
        'reply_to_address', 'reply_to_name', 'is_active',
    ];

    protected $casts = [
        'port' => 'integer',
        'is_active' => 'boolean',
    ];

    protected $hidden = ['password'];

    public static function current(): self
    {
        $row = static::first();
        if (!$row) {
            $row = static::create([
                'mailer' => 'smtp',
                'host' => 'smtp.example.com',
                'port' => 587,
                'encryption' => 'tls',
                'username' => null,
                'password' => null,
                'from_address' => 'info@shishifootsteps.com',
                'from_name' => 'Shishi Footsteps',
                'is_active' => false,
            ]);
        }
        return $row;
    }

    public function applyToConfig(): void
    {
        config([
            'mail.default' => $this->is_active ? $this->mailer : 'log',
            'mail.mailers.smtp.host' => $this->host,
            'mail.mailers.smtp.port' => $this->port,
            'mail.mailers.smtp.encryption' => $this->encryption === 'none' ? null : $this->encryption,
            'mail.mailers.smtp.username' => $this->username,
            'mail.mailers.smtp.password' => $this->password,
            'mail.from.address' => $this->from_address,
            'mail.from.name' => $this->from_name,
        ]);
    }
}
