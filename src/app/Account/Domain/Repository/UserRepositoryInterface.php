<?php declare(strict_types=1);

namespace App\Account\Domain\Repository;

use App\Shared\Domain\Repository\UserRepositoryInterface as RepositoryInterface;
use App\Account\Domain\User;

interface UserRepositoryInterface extends RepositoryInterface
{
	/**
     * Save the given User entity.
     *
     * @param User $user
     */
	public function save(User $user): void;

    /**
     * Remove the given User entity.
     *
     * @param User $user
     */
    public function remove(User $user): void;
}
