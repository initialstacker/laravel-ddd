<?php declare(strict_types=1);

namespace App\Account\Application\Auth\Logout;

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
     * Handler to process user logout by invalidating the token.
     *
     * @param LogoutCommand $command
     * @return bool
     *
     * @throws \RuntimeException
     */
    public function handle(LogoutCommand $command): bool
    {
        try {
            return $this->auth->invalidateToken();
        }

        catch (\Throwable $e) {
            $message = trim(string: <<<MSG
                Logout handler error: {$e->getMessage()}
                in {$e->getFile()}:{$e->getLine()}
            MSG);
            
            Log::error(message: $message, context: [
                'exception' => $e
            ]);

            throw new \RuntimeException(
                message: 'Logout failed. Please try again',
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }
}
