<?php declare(strict_types=1);

namespace App\Account\Application\Profile\Show;

use App\Shared\Application\Handler;
use App\Shared\Domain\Id\UserId;
use App\Account\Domain\Repository\UserRepositoryInterface;
use App\Account\Domain\User;

final class ShowProfileHandler extends Handler
{
    /**
     * Constructs a new ShowProfileHandler instance.
     *
     * @param UserRepositoryInterface $repository
     */
    public function __construct(
        private UserRepositoryInterface $repository
    ) {}

    /**
     * Handles ShowProfileQuery to retrieve a User by ID.
     *
     * @param ShowProfileQuery $query
     * @return User|null
     */
    public function handle(ShowProfileQuery $query): ?User
    {
        return $this->repository->findById(
            id: $query->userId
        );
    }
}
