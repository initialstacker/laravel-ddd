<?php declare(strict_types=1);

namespace App\Account\Application\User\List;

use App\Shared\Application\Handler;
use App\Account\Domain\Repository\UserRepositoryInterface;

final class ListUserHandler extends Handler
{
    /**
     * Constructs a new ListUserHandler instance.
     *
     * @param UserRepositoryInterface $repository
     */
    public function __construct(
        private UserRepositoryInterface $repository
    ) {}

    /**
     * Handles the request to list users.
     *
     * @param ListUserQuery $query
     * @return User[]|null
     */
    public function handle(ListUserQuery $query): array
    {
        return $this->repository->all();
    }
}
