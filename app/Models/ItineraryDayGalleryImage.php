<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ItineraryDayGalleryImage extends Model
{
    protected $fillable = ['galleryable_type', 'galleryable_id', 'image_path', 'caption', 'credit', 'sort_order', 'layout'];
    public function galleryable() { return $this->morphTo(); }
}
