<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'department',
        'phone',
        'is_active',
        'password',
        'two_factor_secret',
        'two_factor_pending_secret',
        'two_factor_confirmed_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_pending_secret',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    public static function roles(): array
    {
        return [
            'administrator' => 'Administrator',
            'sales' => 'Sales Consultant',
            'reservations' => 'Reservations',
            'operations' => 'Operations',
            'finance' => 'Finance',
            'marketing' => 'Marketing',
            'viewer' => 'Viewer',
        ];
    }

    public function initials(): string
    {
        return collect(explode(' ', $this->name))->filter()->take(2)
            ->map(fn ($part) => strtoupper(substr($part, 0, 1)))->implode('');
    }

    public function twoFactorEnabled(): bool
    {
        return filled($this->two_factor_secret) && filled($this->two_factor_confirmed_at);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'assigned_consultant_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'assigned_consultant_id');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }
}
