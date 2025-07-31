<?php declare(strict_types=1);

namespace App\Account\Domain\Relationship;

use App\Account\Domain\Role;

trait UserRelationship
{
    /**
     * Set the user's role identifier.
     *
     * @param Role $role
     */
    public function addRole(Role $role): void
    {
        $this->role = $role;
    }
}
