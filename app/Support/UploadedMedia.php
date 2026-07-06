<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class UploadedMedia
{
    public const POSITIONS = ['center', 'top', 'bottom', 'left', 'right'];

    public static function exists(?string $path): bool
    {
        return filled($path)
            && ! str_contains($path, '..')
            && Storage::disk('public')->exists(ltrim($path, '/'));
    }

    public static function url(?string $path): ?string
    {
        if (! self::exists($path)) {
            return null;
        }

        return route('media.show', ['path' => ltrim($path, '/')], false);
    }

    public static function position(?string $position): string
    {
        return in_array($position, self::POSITIONS, true) ? $position : 'center';
    }
}
