<?php declare(strict_types=1);

namespace App\Account\Domain\Relationship;

use App\Account\Domain\Role;

trait PermissionRelationship
{
    /**
     * Assign a role to the permission.
     *
     * @param Role $role
     */
    public function addRole(Role $role): void
    {
        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
            $role->addPermission(permission: $this);
        }
    }

    /**
     * Remove a role from the permission.
     *
     * @param Role $role
     */
    public function removeRole(Role $role): void
    {
        if ($this->roles->removeElement($role)) {
            $role->removePermission(permission: $this);
        }
    }

    /**
     * Clear all roles from the permission.
     */
    public function clearRoles(): void
    {
        $this->roles->clear();
    }
}
