<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{

    protected $fillable = [
        'name', 'email', 'phone', 'country', 'preferred_language', 'status',
        'passport_number', 'nationality', 'company', 'notes', 'city', 'address',
        'postal_code', 'date_of_birth', 'id_document', 'id_document_type',
        'emergency_contact_name', 'emergency_contact_phone', 'newsletter', 'tags',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'newsletter' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }

    public function getInitialsAttribute(): string
    {
        return collect(explode(' ', $this->name))
            ->filter()
            ->take(2)
            ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
            ->implode('');
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where('name', 'like', "%{$term}%")
            ->orWhere('email', 'like', "%{$term}%")
            ->orWhere('phone', 'like', "%{$term}%");
    }
}
