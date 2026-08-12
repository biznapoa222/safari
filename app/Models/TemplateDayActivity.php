<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemplateDayActivity extends Model
{
    protected $fillable = [
        'template_day_id', 'activity_id', 'activity_name', 'description',
        'start_time', 'end_time', 'price', 'is_optional', 'is_included', 'sort_order',
    ];

    protected function casts(): array
    {
        return ['is_optional' => 'boolean', 'is_included' => 'boolean', 'price' => 'decimal:2'];
    }

    public function templateDay(): BelongsTo { return $this->belongsTo(TemplateDay::class); }
    public function activity(): BelongsTo { return $this->belongsTo(Activity::class); }
}
