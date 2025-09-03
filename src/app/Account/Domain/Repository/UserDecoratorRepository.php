<?php declare(strict_types=1);

namespace App\Account\Domain\Repository;

use App\Shared\Domain\Id\UserId;
use App\Shared\Domain\Email\Email;
use App\Account\Domain\User;

abstract class UserDecoratorRepository implements UserRepositoryInterface
{
    /**
     * Find a User entity by its unique identifier.
     *
     * @param UserId $id
     * @return User|null
     */
    abstract public function findById(UserId $id): ?User;

    /**
     * Find a User entity by its email address.
     *
     * @param Email $email
     * @return User|null
     */
    abstract public function findByEmail(Email $email): ?User;

    /**
     * Find a User entity by its unique identifier and token.
     *
     * @param UserId $id
     * @param string $token
     * 
     * @return User|null
     */
    abstract public function findByToken(UserId $id, string $token): ?User;
}
