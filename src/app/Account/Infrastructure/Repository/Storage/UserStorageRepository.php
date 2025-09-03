<?php declare(strict_types=1);

namespace App\Account\Infrastructure\Repository\Storage;

use App\Account\Domain\User;
use App\Shared\Domain\Email\Email;
use App\Shared\Domain\Id\UserId;
use App\Account\Domain\Repository\UserDecoratorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;

final class UserStorageRepository extends UserDecoratorRepository
{
    /**
     * Doctrine EntityManager instance for DB operations.
     * 
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    /**
     * Find a User by its unique identifier.
     *
     * @param UserId $id
     * @return User|null
     */
    public function findById(UserId $id): ?User
    {
        return $this->entityManager->getRepository(
            className: User::class
        )->find(
            id: $id
        );
    }

    /**
     * Find a User by their Email.
     *
     * @param Email $email
     * @return User|null
     */
    public function findByEmail(Email $email): ?User
    {
        return $this->entityManager->getRepository(
            className: User::class
        )->findOneBy(
            criteria: ['email.email' => $email]
        );
    }

    /**
     * Find a User by their unique identifier and remember token.
     *
     * @param UserId $id
     * @param string $token
     * 
     * @return User|null
     */
    public function findByToken(UserId $id, string $token): ?User
    {
        return $this->entityManager->getRepository(
            className: User::class
        )->findOneBy(
            criteria: [
                'remember_token' => $token,
                'id' => $id,
            ]
        );
    }

    /**
     * Save the given User entity.
     *
     * @param User $user
     * @throws \RuntimeException
     */
    public function save(User $user): void
    {
        try {
            $this->entityManager->persist(object: $user);
            $this->entityManager->flush();
        }

        catch (ORMException $e) {
            throw new \RuntimeException(
                message: "Failed to save user: {$e->getMessage()}",
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }

    /**
     * Remove the given User entity.
     *
     * @param User $user
     * @throws \RuntimeException
     */
    public function remove(User $user): void
    {
        try {
            $this->entityManager->remove(object: $user);
            $this->entityManager->flush();
        }

        catch (ORMException $e) {
            throw new \RuntimeException(
                message: "Failed to save user: {$e->getMessage()}",
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }
}
