<?php declare(strict_types=1);

namespace App\Account\Application\Register\Handler;

use App\Shared\Application\Handler;
use App\Shared\Domain\Slug\RoleSlug;
use App\Account\Domain\Repository\RoleRepositoryInterface;
use App\Account\Application\Register\RegisterCommand;

final class FetchUserRoleHandler extends Handler
{
    /**
     * Constructs a new FetchUserRoleHandler instance.
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
     * @return mixed
     */
    public function handle(RegisterCommand $command, \Closure $next): mixed
    {
        $role = $this->repository->findBySlug(
            slug: RoleSlug::fromString(value: 'user')
        );

        if (is_null(value: $role)) {
            throw new \RuntimeException(
                message: 'Role "user" not found.'
            );
        }
        
        $command->role = $role;

        return $next($command);
    }
}
