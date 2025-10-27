<?php declare(strict_types=1);

namespace App\Account\Infrastructure\Repository;

use App\Account\Domain\Role;
use App\Account\Domain\Repository\RoleDecoratorRepository;
use App\Account\Infrastructure\Repository\Storage\RoleStorageRepository;
use App\Shared\Domain\Slug\RoleSlug;
use App\Shared\Domain\Id\RoleId;

final class RoleRepository extends RoleDecoratorRepository
{
    /**
     * Injects role storage and transaction repositories.
     *
     * @param RoleStorageRepository $storage
     * @param RoleTransactionRepository $transaction
     */
    public function __construct(
        private RoleStorageRepository $storage,
        private RoleTransactionRepository $transaction
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
     * Find a Role entity by its RoleId.
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

    /**
     * Save a Role entity using transactional repository.
     *
     * @param Role $role
     */
    public function save(Role $role): void
    {
        $this->transaction->save(role: $role);
    }

    /**
     * Remove a Role entity using transactional repository.
     *
     * @param Role $role
     */
    public function remove(Role $role): void
    {
        $this->transaction->remove(role: $role);
    }
}
