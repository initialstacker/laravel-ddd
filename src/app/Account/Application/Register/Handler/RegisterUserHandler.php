<?php declare(strict_types=1);

namespace App\Account\Application\Register\Handler;

use App\Shared\Application\Handler;
use App\Shared\Domain\Email\Email;
use App\Account\Domain\Repository\UserRepositoryInterface;
use App\Account\Application\Register\RegisterCommand;
use App\Account\Domain\User;
use App\Account\Domain\Password\Password;
use Illuminate\Support\Facades\Log;

final class RegisterUserHandler extends Handler
{
    /**
     * Constructs a new RegisterUserHandler instance.
     *
     * @param UserRepositoryInterface $repository
     */
    public function __construct(
        private UserRepositoryInterface $repository
    ) {}

    /**
     * Handler to create a user, assign a role, and save the user.
     *
     * @param RegisterCommand $command
     * @param \Closure $next
     * 
     * @return bool
     * 
     * @throws \RuntimeException
     * @throws \Throwable
     */
    public function handle(
        RegisterCommand $command, \Closure $next): bool
    {
        try {
            $user = new User(
                name: $command->name,
                email: Email::fromString(value: $command->email),
                password: Password::fromPlain(
                    value: $command->password
                ),
            );

            $user->addRole(role: $command->role);
            $this->repository->save(user: $user);
            
            return $user !== null;
        }

        catch (\Exception $e) {
            Log::error(
                message: "User registration failed: {$e->getMessage()}",
                context: ['exception' => $e]
            );

            throw new \RuntimeException(
                message: 'User registration failed.',
                code: (int) $e->getCode(),
                previous: $e
            );
        }

        catch (\Throwable $e) {
            Log::critical(
                message: 'Unexpected error: ' . $e->getMessage(),
                context: ['exception' => $e]
            );
            
            throw $e;
        }
    }
}
