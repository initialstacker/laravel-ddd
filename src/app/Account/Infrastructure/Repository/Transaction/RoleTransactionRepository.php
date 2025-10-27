<?php declare(strict_types=1);

namespace App\Account\Infrastructure\Repository\Transaction;

use App\Account\Domain\Role;
use App\Account\Domain\Repository\RoleRepositoryInterface;
use App\Account\Infrastructure\Repository\Storage\RoleStorageRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

final class RoleTransactionRepository implements RoleRepositoryInterface
{
    /**
     * Injects RoleStorageRepository for transactional DB operations.
     * 
     * @param RoleStorageRepository $storage
     */
    public function __construct(
        private readonly RoleStorageRepository $storage
    ) {}

    /**
     * Save the given Role entity within a transaction.
     *
     * @param Role $role
     * @throws \RuntimeException
     */
    public function save(Role $role): void
    {
        try {
            DB::transaction(
                callback: function () use ($role): void {
                    $this->storage->save(role: $role);
                },
                attempts: 3
            );
        }

        catch (QueryException $e) {
            throw new \RuntimeException(
                message: 'Error saving role: ' . $e->getMessage(),
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }
    
    /**
     * Remove the given Role entity within a transaction.
     *
     * @param Role $role
     * @throws \RuntimeException
     */
    public function remove(Role $role): void
    {
        try {
            DB::transaction(
                callback: function () use ($role): void {
                    $this->storage->remove(role: $role);
                },
                attempts: 3
            );
        }

        catch (QueryException $e) {
            throw new \RuntimeException(
                message: 'Error removing role: ' . $e->getMessage(),
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }
}
