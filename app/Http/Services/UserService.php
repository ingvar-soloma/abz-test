<?php

namespace App\Http\Services;

use App\Http\Repositories\UserRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserService extends BaseService
{
    public function __construct(readonly protected UserRepository $repository)
    {
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
}
