<?php declare(strict_types=1);

namespace App\Account\Domain\Changer;

use App\Shared\Domain\Slug\PermissionSlug;
use App\Account\Domain\Guard;

trait PermissionStateChange
{
    /**
     * Change the permission's name.
     *
     * @param string $name
     */
    public function changeName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Change the permission's slug.
     *
     * @param PermissionSlug $slug
     */
    public function changeSlug(PermissionSlug $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * Change the guard type.
     *
     * @param Guard $guard
     */
    public function changeGuard(Guard $guard): void
    {
        $this->guard = $guard;
    }
}
