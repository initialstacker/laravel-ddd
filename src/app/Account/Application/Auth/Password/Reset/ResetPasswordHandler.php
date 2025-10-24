<?php declare(strict_types=1);

namespace App\Account\Application\Auth\Password\Reset;

use App\Shared\Application\Handler;
use App\Account\Infrastructure\Auth\AuthUserAdapter;
use App\Account\Domain\Password\Password;
use App\Account\Domain\Repository\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password as PasswordEvent;
use Illuminate\Support\Facades\Log;

final class ResetPasswordHandler extends Handler
{
    /**
     * Constructs a new ResetPasswordHandler instance.
     *
     * @param UserRepositoryInterface $repository
     */
    public function __construct(
        private UserRepositoryInterface $repository
    ) {}
    
    /**
     * Handles password reset with token and credentials.
     *
     * @param ResetPasswordCommand $command
     * @return bool
     *
     * @throws \RuntimeException
     */
    public function handle(ResetPasswordCommand $command): bool
    {
        try {
            $status = PasswordEvent::reset(
                credentials: [
                    'email' => $command->email,
                    'password' => $command->password,
                    'password_confirmation' => $command->passwordConfirmation,
                    'token' => $command->token
                ],
                callback: function (AuthUserAdapter $auth, string $password): void {
                    $auth->user->changePassword(
                        password: Password::fromPlain(
                            value: $password
                        )
                    );

                    $this->repository->save(user: $auth->user);
                }
            );

            return $status === PasswordEvent::PASSWORD_RESET;
        }

        catch (\Throwable $e) {
            $message = trim(string: <<<MSG
                Reset password handler error: {$e->getMessage()}
                in {$e->getFile()}:{$e->getLine()}
            MSG);

            Log::error(message: $message, context: [
                'exception' => $e,
                'email' => $command->email,
            ]);

            throw new \RuntimeException(
                message: 'Password reset failed. Please try again.',
                code: (int)$e->getCode(),
                previous: $e
            );
        }
    }
}
