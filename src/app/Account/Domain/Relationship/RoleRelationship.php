<?php declare(strict_types=1);

namespace App\Account\Domain\Relationship;

use App\Account\Domain\Permission;

trait RoleRelationship
{
    /**
     * Assign a permission to the role.
     *
     * @param Permission $permission
     */
    public function addPermission(Permission $permission): void
    {
        if (!$this->permissions->contains($permission)) {
            $this->permissions->add($permission);
            $permission->addRole(role: $this);
        }
    }

    /**
     * Remove a permission from the role.
     *
     * @param Permission $permission
     */
    public function removePermission(Permission $permission): void
    {
        if ($this->permissions->removeElement($permission)) {
            $permission->removeRole(role: $this);
        }
    }
}
