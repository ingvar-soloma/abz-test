<?php

namespace App\Jobs;

use App\Http\Services\TinyPngService;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\UploadedFile;

class OptimizePhotoJob implements ShouldQueue
{
    use Queueable;

    private string $photoPath;
    private int $userId;

    public function __construct(string $photoPath, int $userId)
    {
        $this->photoPath = $photoPath;
        $this->userId = $userId;
    }

    final public function handle(TinyPngService $tinyPngService): void
    {
        // Process and optimize the photo using the TinyPngService
        $optimizedPath = $tinyPngService->processAndOptimizePhoto($this->photoPath);

        // Save the optimized image path to the user (you can store it in the database)
        // Assuming you have a User model, and you want to associate the optimized photo path with the user.
        $user = User::find($this->userId);
        $user->photo = $optimizedPath;
        $user->save();
    }
}
