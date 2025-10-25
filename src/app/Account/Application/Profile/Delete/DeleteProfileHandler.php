<?php declare(strict_types=1);

namespace App\Account\Application\Profile\Delete;

use App\Shared\Application\Handler;
use App\Account\Domain\Repository\UserRepositoryInterface;
use Illuminate\Support\Facades\{Auth, Log};

final class DeleteProfileHandler extends Handler
{
    /**
     * Constructs a new DeleteProfileHandler instance.
     *
     * @param UserRepositoryInterface $repository
     */
    public function __construct(
        private UserRepositoryInterface $repository
    ) {}

    /**
     * Handles profile deletion for the authenticated user.
     *
     * @param DeleteProfileCommand $command
     * @return bool
     */
    public function handle(DeleteProfileCommand $command): bool
    {
        try {
            $auth = Auth::user();

            if ($auth === null) {
                throw new \RuntimeException(
                    message: 'No authenticated user found.'
                );
            }

            $user = $this->repository->findById(
                id: $auth->user->id
            );

            if ($user === null) {
                throw new \RuntimeException(
                    message: 'User not found for the authenticated ID.'
                );
            }

            $this->repository->remove(user: $user);

            return $user === null;
        }

        catch (\Throwable $e) {
            $message = trim(string: <<<MSG
                Delete profile handler error: {$e->getMessage()}
                in {$e->getFile()}:{$e->getLine()}
            MSG);

            Log::error(message: $message, context: [
                'exception' => $e
            ]);

            throw new \RuntimeException(
                message: 'Failed to delete profile due to error',
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }
}
