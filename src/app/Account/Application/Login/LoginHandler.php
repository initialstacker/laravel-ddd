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
        private readonly AuthProviderInterface $auth
    ) {}

    /**
     * Processes login and returns a JWT token on success.
     *
     * @param LoginCommand $command
     * @return array<string, string>|null
     */
    public function handle(LoginCommand $command): ?array
    {
        try {
            return $this->auth->getTokenByCredentials(
                credentials: $command->toArray()
            );
        }

        catch (\Throwable $e) {
            Log::error(
                message: 'Login error: ' . $e->getMessage(),
                context: ['exception' => $e]
            );

            return null;
        }
    }
}
