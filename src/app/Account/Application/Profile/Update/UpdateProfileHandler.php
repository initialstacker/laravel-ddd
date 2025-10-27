<?php declare(strict_types=1);

namespace App\Account\Application\Profile\Update;

use App\Shared\Application\Handler;
use App\Shared\Domain\Email\Email;
use App\Account\Domain\Repository\UserRepositoryInterface;
use App\Account\Domain\Password\Password;
use Illuminate\Support\Facades\{Auth, Log};

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

            if ($auth === null || !isset($auth->user->id)) {
                throw new \RuntimeException(
                    message: 'Authenticated user not found.'
                );
            }

            $user = $this->repository->findById(id: $authUser->user->id);

            if ($user === null) {
                throw new \RuntimeException(
                    message: "User with ID {$authUser->user->id} not found."
                );
            }

            $email = Email::fromString(value: $command->email);
            $password = Password::fromPlain(value: $command->password);

            $hasChanges = false;

            if ($user->getName() !== $command->name) {
                $user->changeName(name: $command->name);
                $hasChanges = true;
            }

            if (!$user->getEmail()->equals(other: $email)) {
                $user->changeEmail(email: $email);
                $hasChanges = true;
            }

            if (!$user->getPassword()->equals(other: $password)) {
                $user->changePassword(password: $password);
                $hasChanges = true;
            }

            if (!$hasChanges) {
                return false;
            }

            $this->repository->save(user: $user);

            return true;
        }

        catch (\Throwable $e) {
            $message = trim(string: <<<MSG
                Update profile error: {$e->getMessage()}
                in {$e->getFile()}:{$e->getLine()}
            MSG);

            Log::error(message: $message, context: [
                'exception' => $e,
            ]);

            throw new \RuntimeException(
                message: 'Failed to update profile due to error',
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }
}
