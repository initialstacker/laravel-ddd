<?php declare(strict_types=1);

namespace App\Account\Infrastructure\Repository;

use App\Account\Domain\Repository\UserDecoratorRepository;
use App\Account\Domain\{User, Email\Email};
use App\Account\Infrastructure\Repository\Storage\UserStorageRepository;
use App\Account\Infrastructure\Repository\Transaction\UserTransactionRepository;
use App\Shared\Domain\Id\UserId;

final class UserRepository extends UserDecoratorRepository
{
    /**
     * Injects user storage and transaction repositories.
     *
     * @param UserStorageRepository $storage
     * @param UserTransactionRepository $transaction
     */
    public function __construct(
        private UserStorageRepository $storage,
        private UserTransactionRepository $transaction
    ) {}

    /**
     * Find a User entity by its unique identifier.
     *
     * @param UserId $id
     * @return User|null
     */
    public function findById(UserId $id): ?User
    {
        return $this->storage->findById(id: $id);
    }

    /**
     * Find a User entity by its email address.
     *
     * @param Email $email
     * @return User|null
     */
    public function findByEmail(Email $email): ?User
    {
        return $this->storage->findByEmail(email: $email);
    }

    /**
     * Find a User entity by its unique identifier and token.
     *
     * @param UserId $id
     * @param string $token
     * 
     * @return User|null
     */
    public function findByToken(UserId $id, string $token): ?User
    {
        return $this->storage->findByToken(
            id: $id,
            token: $token
        );
    }

    /**
     * Save a User entity using transactional repository.
     *
     * @param User $user
     */
    public function save(User $user): void
    {
        $this->transaction->save(user: $user);
    }

    public function remove(User $user): void
    {
        $this->transaction->remove(user: $user);
    }
}
