<?php declare(strict_types=1);

namespace App\Account\Domain\Repository;

use App\Shared\Domain\Repository\RoleRepositoryInterface as RepositoryInterface;
use App\Account\Domain\Role;

interface RoleRepositoryInterface
{
    /**
     * Save the given Role entity.
     *
     * @param Role $role
     */
    public function save(Role $role): void;

    /**
     * Remove the given Role entity.
     *
     * @param Role $role
     */
    public function remove(Role $role): void;
}
