<?php declare(strict_types=1);

namespace App\Account\Application\Role\Create;

use App\Shared\Application\Handler;
use App\Account\Domain\Repository\RoleRepositoryInterface;
use App\Account\Domain\Role;
use App\Shared\Domain\Slug\RoleSlug as Slug;
use Illuminate\Support\Facades\Log;

final class CreateRoleHandler extends Handler
{
    /**
     * Constructs a new CreateRoleHandler instance.
     * 
     * @param RoleRepositoryInterface $repository
     */
    public function __construct(
        private RoleRepositoryInterface $repository
    ) {}

    /**
     * Handles the creation of a new role.
     *
     * @param CreateRoleCommand $command
     * @return bool
     *
     * @throws \RuntimeException
     */
    public function handle(CreateRoleCommand $command): bool
    {
        try {
            $role = new Role(
                name: $command->name,
                slug: Slug::fromString(value: $command->slug)
            );

            $this->repository->save(role: $role);
            
            return $role !== null;
        }

        catch (\Throwable $e) {
            $message = trim(string: <<<MSG
                Create role handler error: {$e->getMessage()}
                in {$e->getFile()}:{$e->getLine()}
            MSG);

            Log::error(message: $message, context: [
                'exception' => $e
            ]);

            throw new \RuntimeException(
                message: 'Failed to create role due to error',
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }
}
