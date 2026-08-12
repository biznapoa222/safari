<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CmsContentBlock extends Model
{
    protected $fillable = ['page', 'key', 'type', 'value'];

    public static function value(string $page, string $key, ?string $fallback = null): ?string
    {
        $values = Cache::remember("cms.content.{$page}", 3600, fn () => static::where('page', $page)->pluck('value', 'key')->all());
        $default = config("cms.pages.{$page}.fields.{$key}.default", $fallback);
        return array_key_exists($key, $values) && $values[$key] !== null && $values[$key] !== '' ? $values[$key] : $default;
    }

    public static function flushPage(string $page): void
    {
        Cache::forget("cms.content.{$page}");
    }
}
