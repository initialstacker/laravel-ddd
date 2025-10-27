<?php declare(strict_types=1);

namespace App\Account\Infrastructure\Repository;

use App\Account\Domain\Role;
use App\Account\Domain\Repository\RoleRepositoryInterface;
use App\Account\Infrastructure\Repository\Storage\RoleStorageRepository;
use App\Shared\Domain\Slug\RoleSlug;
use App\Shared\Domain\Id\RoleId;

final class RoleRepository implements RoleRepositoryInterface
{
    /**
     * Injects repositories for reading and writing Role entities.
     *
     * @param RoleStorageRepository $storage
     */
    public function __construct(
        private RoleStorageRepository $storage,
    ) {}

    /**
     * Return all Role entities.
     *
     * @return Role[]
     */
    public function all(): array
    {
        return $this->storage->all();
    }

    /**
     * Find a Role entity by its UserId.
     *
     * @param RoleId $id
     * @return Role|null
     */
    public function findById(RoleId $id): ?Role
    {
        return $this->storage->findById(id: $id);
    }

    /**
     * Find a Role entity by its slug.
     *
     * @param RoleSlug $slug
     * @return Role|null
     */
    public function findBySlug(RoleSlug $slug): ?Role
    {
        return $this->storage->findBySlug(slug: $slug);
    }
}
