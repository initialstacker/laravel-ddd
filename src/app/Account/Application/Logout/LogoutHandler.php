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
        private AuthProviderInterface $auth
    ) {}

    /**
     * Handler to process logout by invalidating the token.
     *
     * @param LogoutCommand $command
     * @return bool
     * 
     * @throws \RuntimeException
     * @throws \Throwable
     */
    public function handle(LogoutCommand $command): bool
    {
        try {
            return $this->auth->invalidateToken();
        }

        catch (\Exception $e) {
            Log::error(
                message: "Logout failed: {$e->getMessage()}",
                context: ['exception' => $e]
            );

            throw new \RuntimeException(
                message: 'Logout failed. Please try again',
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
