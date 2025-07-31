<?php declare(strict_types=1);

namespace App\Account\Domain\Changer;

use App\Shared\Domain\Slug\RoleSlug;

trait RoleStateChange
{
    /**
     * Change the role's name.
     *
     * @param string $name
     */
    public function changeName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Change the role's slug.
     *
     * @param RoleSlug $slug
     */
    public function changeSlug(RoleSlug $slug): void
    {
        $this->slug = $slug;
    }
}
