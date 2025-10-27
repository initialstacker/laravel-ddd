<?php declare(strict_types=1);

namespace App\Account\Infrastructure\Repository\Storage;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use App\Account\Domain\Role;
use App\Account\Domain\Repository\RoleRepositoryInterface;
use App\Shared\Domain\Id\RoleId;
use App\Shared\Domain\Slug\RoleSlug;

final class RoleStorageRepository implements RoleRepositoryInterface
{
    /**
     * Doctrine EntityManager instance for DB operations.
     * 
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * Return all Role entities.
     * 
     * @return Role[]
     */
    public function all(): array
    {
        return $this->entityManager->getRepository(
            className: Role::class
        )->findAll();
    }

    /**
     * Find a Role entity by its UserId.
     *
     * @param RoleId $id
     * @return Role|null
     */
    public function findById(RoleId $id): ?Role
    {
        return $this->entityManager->getRepository(
            className: Role::class
        )->find(
            id: $id
        );
    }

    /**
     * Find a Role entity by its slug.
     *
     * @param RoleSlug $slug
     * @return Role|null
     */
    public function findBySlug(RoleSlug $slug): ?Role
    {
        return $this->entityManager->getRepository(
            className: Role::class
        )->findOneBy(
            criteria: ['slug' => $slug]
        );
    }

    /**
     * Save the given Role entity.
     *
     * @param Role $role
     * @throws \RuntimeException
     */
    public function save(Role $role): void
    {
        try {
            $this->entityManager->persist(object: $role);
            $this->entityManager->flush();
        }

        catch (ORMException $e) {
            throw new \RuntimeException(
                message: "Failed to save role: {$e->getMessage()}",
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }

    /**
     * Remove the given Role entity.
     *
     * @param Role $role
     * @throws \RuntimeException
     */
    public function remove(Role $role): void
    {
        try {
            $this->entityManager->remove(object: $role);
            $this->entityManager->flush();
        }

        catch (ORMException $e) {
            throw new \RuntimeException(
                message: "Failed to save role: {$e->getMessage()}",
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }
}
