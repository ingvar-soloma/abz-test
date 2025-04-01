<?php

namespace App\Http\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tinify\Tinify;
use function Tinify\fromFile;

class TinyPngService
{
    public function __construct()
    {
        \Tinify\setKey(config('services.tinify.key'));
    }

    public function processAndOptimizePhoto(UploadedFile $photo): string
    {
        // 📌 1. Save the photo to the temporary folder
        $tempPath = 'photos/temp_' . time() . '.jpg';
        Storage::disk('public')->put($tempPath, file_get_contents($photo));

        // 📌 2. Optimize and resize the photo
        $optimizedPath = $this->optimizeAndResizeWithTinyPNG($tempPath);

        // 📌 3. Delete the temporary photo
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
