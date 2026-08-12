<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ProposalTemplateSetting extends Model
{
    protected $fillable = ['itinerary_template_id', 'settings'];
    protected function casts(): array { return ['settings' => 'array']; }
    public function template()
    {
        return $this->belongsTo(ItineraryTemplate::class, 'itinerary_template_id');
    }
}
