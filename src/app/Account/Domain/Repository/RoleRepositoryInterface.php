<?php declare(strict_types=1);

namespace App\Account\Domain\Repository;

use App\Shared\Domain\Id\RoleId;
use App\Shared\Domain\Slug\RoleSlug;
use App\Account\Domain\Role;

interface RoleRepositoryInterface
{
	/**
     * Find a Role entity by its slug.
     *
     * @param RoleSlug $slug
     * @return Role|null
     */
    public function findBySlug(RoleSlug $slug): ?Role;

    /**
     * Find a Role entity by its ID.
     *
     * @param RoleId $id
     * @return Role|null
     */
    public function findById(RoleId $id): ?Role;
}
