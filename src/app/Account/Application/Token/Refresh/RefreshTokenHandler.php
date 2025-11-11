<?php declare(strict_types=1);

namespace App\Account\Application\Token\Refresh;

use App\Shared\Application\Handler;
use App\Account\Domain\Provider\AuthProviderInterface;
use Illuminate\Support\Facades\Log;

final class RefreshTokenHandler extends Handler
{
    /**
     * Constructs a new RefreshTokenHandler instance.
     *
     * @param AuthProviderInterface $auth
     */
    public function __construct(
        private readonly AuthProviderInterface $auth
    ) {}

    /**
     * Handler to refresh the JWT access token.
     *
     * @param RefreshTokenCommand $command
     * @return string|null
     * 
     * @throws \RuntimeException
     * @throws \Throwable
     */
    public function handle(RefreshTokenCommand $command): ?string
    {
        try {
            $newToken = $this->auth->refreshToken();

            if ($newToken === null) {
                throw new \RuntimeException(
                    message: 'Token refresh failed.'
                );
            }

            return $newToken;
        }

        catch (\Exception $e) {
            Log::error(
                message: "Failed to refresh token: {$e->getMessage()}",
                context: ['exception' => $e]
            );

            throw new \RuntimeException(
                message: 'Failed to refresh token.',
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
