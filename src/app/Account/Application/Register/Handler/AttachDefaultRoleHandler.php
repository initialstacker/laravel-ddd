<?php declare(strict_types=1);

namespace App\Account\Application\Register\Handler;

use App\Shared\Application\Handler;
use App\Account\Domain\Repository\RoleRepositoryInterface;
use App\Account\Domain\Role;
use App\Account\Application\Register\RegisterCommand;
use App\Shared\Domain\Slug\RoleSlug;

final class AttachDefaultRoleHandler extends Handler
{
    /**
     * Constructs a new AttachDefaultRoleHandler instance.
     *
     * @param RoleRepositoryInterface $repository
     */
    public function __construct(
        private RoleRepositoryInterface $repository
    ) {}

    /**
     * Handles fetching the 'user' role and adds it to the command.
     *
     * @param RegisterCommand $command
     * @param \Closure $next
     * 
     * @return Role|null
     */
    public function handle(
        RegisterCommand $command, \Closure $next): ?Role
    {
        $role = $this->repository->findBySlug(
            slug: RoleSlug::fromString(value: 'user')
        );

        if (!$role instanceof Role) {
            throw new \RuntimeException(
                message: 'Role "user" not found.'
            );
        }
        
        $command->role = $role;

        return $next($command);
    }
}
