<?php declare(strict_types=1);

namespace App\Account\Application\Profile\Update;

use App\Shared\Application\Handler;
use App\Account\Domain\Repository\UserRepositoryInterface;
use App\Account\Domain\Email\Email;
use App\Account\Domain\Password\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

final class UpdateProfileHandler extends Handler
{
    /**
     * Constructs a new UpdateProfileHandler instance.
     * 
     * @param UserRepositoryInterface $repository
     */
    public function __construct(
        private UserRepositoryInterface $repository
    ) {}

    /**
     * Updates user profile with new name, email, and password.
     * 
     * @param UpdateProfileCommand $command
     * @return bool
     */
    public function handle(UpdateProfileCommand $command): bool
    {
        try {
            /** @var \App\Account\Infrastructure\Auth\AuthUserAdapter|null $auth */
            $auth = Auth::user();
            $user = $this->repository->findById(id: $auth->user->id);

            $user->changeName(name: $command->name);
            $user->changeEmail(
                email: Email::fromString(value: $command->email)
            );
            $user->changePassword(password:
                Password::fromPlain(
                    value: $command->password
                )
            );

            $this->repository->save(user: $user);

            return true;
        }

        catch (\Throwable $e) {
            Log::error(
                message: 'Update profile error: ' . $e->getMessage(),
                context: ['exception' => $e]
            );

            return false;
        }
    }
}
