<?php declare(strict_types=1);

namespace App\Account\Application\Profile\Update;

use App\Shared\Application\Handler;
use App\Shared\Domain\Email\Email;
use App\Account\Domain\Repository\UserRepositoryInterface;
use App\Account\Domain\Avatar;
use App\Account\Domain\Password\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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
     * 
     * @throws \RuntimeException
     * @throws \Throwable
     */
    public function handle(UpdateProfileCommand $command): bool
    {
        try {
            /** @var \App\Account\Infrastructure\Auth\AuthUserAdapter|null $auth */
            $auth = Auth::user();

            if ($auth === null || !isset($auth->user->id)) {
                throw new \RuntimeException(
                    message: 'Authenticated user not found.'
                );
            }

            $user = $this->repository->findById(id: $auth->user->id);

            if ($user === null) {
                throw new \RuntimeException(
                    message: "User with ID {$auth->user->id} not found."
                );
            }

            $password = Password::fromPlain(value: $command->password);
            $email = Email::fromString(value: $command->email);

            $user->changeName(name: $command->name);
            $user->changeEmail(email: $email);
            $user->changePassword(password: $password);

            if ($command->avatar !== null && $command->avatar->isValid()) {
                $avatar = $command->avatar->store(
                    path: 'avatars', options: 'public'
                );

                $user->changeAvatar(
                    avatar: Avatar::fromString(value: $avatar)
                );
            }

            $this->repository->save(user: $user);

            return $user !== null;
        }

        catch (\Exception $e) {
            Log::error(
                message: "Failed to update profile: {$e->getMessage()}",
                context: ['exception' => $e]
            );

            throw new \RuntimeException(
                message: 'Failed to update profile due to error',
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
