<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ItineraryHighlight extends Model
{
    protected $fillable = ['highlightable_type', 'highlightable_id', 'title', 'description', 'icon', 'sort_order'];
    public function highlightable() { return $this->morphTo(); }
}
