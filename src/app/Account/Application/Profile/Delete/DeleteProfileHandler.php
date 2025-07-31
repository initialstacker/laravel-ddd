<?php declare(strict_types=1);

namespace App\Account\Application\Profile\Delete;

use App\Shared\Application\Handler;
use App\Shared\Domain\Id\UserId;
use App\Account\Domain\Repository\UserRepositoryInterface;
use App\Account\Domain\User;

final class DeleteProfileHandler extends Handler
{
    /**
     * Constructs a new DeleteProfileHandler instance.
     *
     * @param UserRepositoryInterface $repository
     */
    public function __construct(
        private UserRepositoryInterface $repository
    ) {}

    /**
     * Handles DeleteProfileQuery
     *
     * @param DeleteProfileQuery $query
     * @return bool
     */
    public function handle(DeleteProfileQuery $query): bool
    {
        try {
            $user = $this->repository->findById(
                id: $query->userId
            );

            if (is_null($user)) {
                throw new \RuntimeException(
                    message: sprintf('
                        User with ID "%s" not found.',
                        $query->userId
                    )
                );
            }

            $this->repository->remove(user: $user);

            return true;
        }

        catch (\Throwable $e) {
            return false;
        }
    }
}
