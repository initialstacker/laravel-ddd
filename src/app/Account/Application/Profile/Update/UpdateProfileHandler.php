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
            $user = $this->repository->findById(id: $auth->user->id);

            $updated = false;

            if ($user->changeName(name: $command->name)) {
                $updated = true;
            }

            $email = Email::fromString(value: $command->email);

            if ($user->changeEmail(email: $email)) {
                $updated = true;
            }

            $password = Password::fromPlain(value: $command->password);
            
            if ($user->changePassword(password: $password)) {
                $updated = true;
            }

            if ($updated) {
                $this->repository->save(user: $user);
            }

            return $updated;
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
