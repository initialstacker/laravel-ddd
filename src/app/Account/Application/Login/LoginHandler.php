<?php declare(strict_types=1);

namespace App\Account\Application\Login;

use App\Shared\Application\Handler;
use App\Account\Domain\Provider\AuthProviderInterface;
use Illuminate\Support\Facades\Log;

final class LoginHandler extends Handler
{
    /**
     * Constructs a new LoginHandler instance.
     *
     * @param AuthProviderInterface $auth
     */
    public function __construct(
        private AuthProviderInterface $auth
    ) {}

    /**
     * Handler to process user login and return a JWT token.
     *
     * @param LoginCommand $command
     * @return array<string, string>|null
     * 
     * @throws \RuntimeException
     * @throws \Throwable
     */
    public function handle(LoginCommand $command): ?array
    {
        try {
            return $this->auth->getTokenByCredentials(
                credentials: $command->toArray()
            );
        }
        
        catch (\Exception $e) {
            Log::error(
                message: "Login failed: {$e->getMessage()}",
                context: ['exception' => $e]
            );

            throw new \RuntimeException(
                message: 'Login failed. Please try again',
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
