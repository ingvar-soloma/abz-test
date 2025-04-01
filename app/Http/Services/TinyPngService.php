<?php

namespace App\Http\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use function Tinify\fromFile;

class TinyPngService
{
    public function __construct()
    {
        \Tinify\setKey(config('services.tinify.key'));
    }

    final public function storeTemporaryPhoto(UploadedFile $photo): string
    {
        $tempPath = 'photos/temp_' . time() . '.jpg';
        Storage::disk('public')->put($tempPath, file_get_contents($photo));

        return $tempPath;
    }

    final public function processAndOptimizePhoto(string $tempPath): string
    {
        $optimizedPath = $this->optimizeAndResizeWithTinyPNG($tempPath);

        Storage::disk('public')->delete($tempPath);

        return $optimizedPath;
    }

    private function optimizeAndResizeWithTinyPNG(string $imagePath): string
    {
        $source = fromFile(Storage::disk('public')->path($imagePath));

        $resized = $source->resize([
            "method" => "cover",
            "width" => 70,
            "height" => 70
        ]);

        $optimizedPath = 'photos/optimized_' . time() . '.jpg';
        $resized->toFile(Storage::disk('public')->path($optimizedPath));

        return $optimizedPath;
    }
}
