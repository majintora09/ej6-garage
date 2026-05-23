<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class DominantColorExtractor
{
    public function fromUploadedFile(UploadedFile $file): ?string
    {
        if (! function_exists('imagecreatetruecolor')) {
            return null;
        }

        $path = $file->getRealPath();

        if (! $path) {
            return null;
        }

        $image = $this->createImage($path, $file->getMimeType());

        if (! $image) {
            return null;
        }

        $width = imagesx($image);
        $height = imagesy($image);
        $sampleSize = 48;
        $sample = imagecreatetruecolor($sampleSize, $sampleSize);

        imagecopyresampled($sample, $image, 0, 0, 0, 0, $sampleSize, $sampleSize, $width, $height);

        $red = 0;
        $green = 0;
        $blue = 0;
        $count = 0;

        for ($x = 0; $x < $sampleSize; $x++) {
            for ($y = 0; $y < $sampleSize; $y++) {
                $rgb = imagecolorat($sample, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;

                if ($this->isMostlyBackground($r, $g, $b)) {
                    continue;
                }

                $red += $r;
                $green += $g;
                $blue += $b;
                $count++;
            }
        }

        imagedestroy($sample);
        imagedestroy($image);

        if ($count === 0) {
            return null;
        }

        return sprintf('#%02x%02x%02x', (int) round($red / $count), (int) round($green / $count), (int) round($blue / $count));
    }

    private function createImage(string $path, ?string $mimeType): mixed
    {
        return match ($mimeType) {
            'image/jpeg' => @imagecreatefromjpeg($path),
            'image/png' => @imagecreatefrompng($path),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
            default => false,
        };
    }

    private function isMostlyBackground(int $red, int $green, int $blue): bool
    {
        $brightness = ($red + $green + $blue) / 3;
        $spread = max($red, $green, $blue) - min($red, $green, $blue);

        return $brightness < 24 || $brightness > 238 || ($spread < 8 && $brightness > 180);
    }
}
