<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class MediaPath
{
    public static function publicUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (preg_match('/^https?:\/\//i', $path)) {
            return $path;
        }

        $path = ltrim($path, '/');

        return str_starts_with($path, 'images/')
            ? asset($path)
            : asset('storage/'.$path);
    }

    public static function localPath(?string $path): ?string
    {
        if (blank($path) || preg_match('/^https?:\/\//i', $path)) {
            return null;
        }

        $path = ltrim($path, '/');
        if (str_starts_with($path, 'images/')) {
            $localPath = public_path($path);

            return is_file($localPath) ? $localPath : null;
        }

        return Storage::disk('public')->exists($path)
            ? Storage::disk('public')->path($path)
            : null;
    }

    public static function isManagedUpload(?string $path): bool
    {
        return filled($path)
            && ! preg_match('/^https?:\/\//i', $path)
            && ! str_starts_with(ltrim($path, '/'), 'images/');
    }
}
