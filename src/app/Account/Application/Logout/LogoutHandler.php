<?php declare(strict_types=1);

namespace App\Account\Application\Logout;

use App\Shared\Application\Handler;
use App\Account\Domain\Provider\AuthProviderInterface;
use Illuminate\Support\Facades\Log;

final class LogoutHandler extends Handler
{
    /**
     * Constructs a new LogoutHandler instance.
     *
     * @param AuthProviderInterface $auth
     */
    public function __construct(
        private readonly AuthProviderInterface $auth
    ) {}

    /**
     * Handles the sign-out query by logging out the current user.
     *
     * @param LogoutQuery $query
     * @return bool
     */
    public function handle(LogoutQuery $query): bool
    {
        try {
            return $this->auth->invalidateToken();
        }

        catch (\Throwable $e) {
            Log::error(
                message: 'Logout error: ' . $e->getMessage(),
                context: ['exception' => $e]
            );

            return false;
        }
    }
}
