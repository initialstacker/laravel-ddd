<?php declare(strict_types=1);

namespace App\Account\Infrastructure\Repository\Storage;

use Doctrine\ORM\EntityManagerInterface;
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
        private readonly EntityManagerInterface $entityManager
    ) {}

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
}
