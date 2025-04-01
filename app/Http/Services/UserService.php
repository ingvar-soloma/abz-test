<?php

namespace App\Http\Services;

use App\Http\Repositories\UserRepository;
use App\Jobs\OptimizePhotoJob;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserService extends BaseService
{
    public function __construct(
        readonly protected UserRepository $repository,
        readonly protected TinyPngService $tinyPngService
    )
    {
    }

    final public function registerUser(array $validated, string $token): Model
    {
        $this->storeTemporaryPhoto($validated);

        $user = $this->repository->create($validated);

        $this->destroyRegistrationToken($token);
        $this->processPhoto($validated, $user->id);

        return $user;
    }

    final public function getAllData(array $validated, array $with): LengthAwarePaginator
    {

        $page = $validated['page'] ?? 1;
        $count = $validated['count'] ?? 5;

        unset($validated['page'], $validated['count']);

        $userPaginator = $this->repository->getAllPaginated($validated, $count, $page, $with);

        if ($userPaginator->currentPage() > $userPaginator->lastPage()) {
            throw new NotFoundHttpException();
        }
        return $userPaginator;
    }

    private function storeTemporaryPhoto(array &$validated): void
    {
        $validated['photo'] = $this->tinyPngService->storeTemporaryPhoto($validated['photo']);
    }

    private function processPhoto(array $validated, int $userId): void
    {
        OptimizePhotoJob::dispatch($validated['photo'], $userId);
    }

    private function destroyRegistrationToken(string $token): void
    {
        $accessToken = PersonalAccessToken::findToken($token);
        $accessToken->delete();
    }
}
