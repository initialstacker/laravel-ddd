<?php declare(strict_types=1);

namespace App\Account\Domain\Repository;

use App\Shared\Domain\Id\RoleId;
use App\Shared\Domain\Slug\RoleSlug;
use App\Account\Domain\Role;

abstract class RoleDecoratorRepository implements RoleRepositoryInterface
{
    /**
     * Return all Role entities.
     *
     * @return Role[]
     */
    abstract public function all(): array;
    
    /**
     * Find a Role entity by its slug.
     *
     * @param RoleSlug $slug
     * @return Role|null
     */
    abstract public function findBySlug(RoleSlug $slug): ?Role;

    /**
     * Find a Role entity by its ID.
     *
     * @param RoleId $id
     * @return Role|null
     */
    abstract public function findById(RoleId $id): ?Role;
}
