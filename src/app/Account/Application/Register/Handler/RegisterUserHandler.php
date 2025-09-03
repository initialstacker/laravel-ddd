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
     * Handler to create user, assign role, save user.
     *
     * @param RegisterCommand $command
     * @param \Closure $next
     * 
     * @return mixed
     */
    public function handle(RegisterCommand $command, \Closure $next): mixed
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
            
            return $next($command);
        }

        catch (\Throwable $e) {
            Log::error(
                message: 'Register error: ' . $e->getMessage(),
                context: ['exception' => $e]
            );

            return $next($command);
        }
    }
}
