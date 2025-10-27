<?php declare(strict_types=1);

namespace App\Account\Application\Profile\Show;

use App\Shared\Application\Handler;
use App\Shared\Domain\Id\UserId;
use App\Account\Domain\Repository\UserRepositoryInterface;
use App\Account\Domain\User;

final class ShowUserHandler extends Handler
{
    /**
     * Constructs a new ShowUserHandler instance.
     *
     * @param UserRepositoryInterface $repository
     */
    public function __construct(
        private UserRepositoryInterface $repository
    ) {}

    /**
     * Handles ShowUserQuery to retrieve a User by ID.
     *
     * @param ShowUserQuery $query
     * @return User|null
     */
    public function handle(ShowUserQuery $query): ?User
    {
        return $this->repository->findById(
            id: UserId::fromString(value: $query->userId)
        );
    }
}
