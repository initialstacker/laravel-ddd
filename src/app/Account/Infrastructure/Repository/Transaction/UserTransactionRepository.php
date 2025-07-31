<?php declare(strict_types=1);

namespace App\Account\Infrastructure\Repository\Transaction;

use App\Account\Domain\User;
use App\Account\Domain\Repository\UserRepositoryInterface;
use App\Account\Infrastructure\Repository\Storage\UserStorageRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

final class UserTransactionRepository implements UserRepositoryInterface
{
    /**
     * Injects UserStorageRepository for transactional DB operations.
     * 
     * @param UserStorageRepository $storage
     */
    public function __construct(
        private readonly UserStorageRepository $storage
    ) {}

    /**
     * Save the given User entity within a transaction.
     *
     * @param User $user
     * @throws \RuntimeException
     */
    public function save(User $user): void
    {
        try {
            DB::transaction(
                callback: function () use ($user): void {
                    $this->storage->save(user: $user);
                },
                attempts: 3
            );
        }

        catch (QueryException $e) {
            throw new \RuntimeException(
                message: 'Error saving user: ' . $e->getMessage(),
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }
    
    /**
     * Remove the given User entity within a transaction.
     *
     * @param User $user
     * @throws \RuntimeException
     */
    public function remove(User $user): void
    {
        try {
            DB::transaction(
                callback: function () use ($user): void {
                    $this->storage->remove(user: $user);
                },
                attempts: 3
            );
        }

        catch (QueryException $e) {
            throw new \RuntimeException(
                message: 'Error removing user: ' . $e->getMessage(),
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }
}
