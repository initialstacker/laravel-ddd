<?php declare(strict_types=1);

namespace App\Account\Application\Auth\Register\Handler;

use App\Shared\Application\Handler;
use App\Shared\Domain\Email\Email;
use App\Account\Domain\Repository\UserRepositoryInterface;
use App\Account\Application\Auth\Register\RegisterCommand;
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

        catch (\Throwable $e) {
            $message = trim(string: <<<MSG
                Register handler error: {$e->getMessage()}
                in {$e->getFile()}:{$e->getLine()}
            MSG);

            Log::error(message: $message, context: [
                'exception' => $e
            ]);

            throw new \RuntimeException(
                message: 'User registration failed.',
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }
}
