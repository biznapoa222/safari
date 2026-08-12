<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['activity_id', 'locale', 'title', 'description', 'location', 'region'];

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }
}
