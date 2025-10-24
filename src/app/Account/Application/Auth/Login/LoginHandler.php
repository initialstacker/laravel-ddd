<?php declare(strict_types=1);

namespace App\Account\Application\Auth\Login;

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
     * Handler to process user login and return a JWT token.
     *
     * @param LoginCommand $command
     * @return array<string, string>|null
     *
     * @throws \RuntimeException
     */
    public function handle(LoginCommand $command): ?array
    {
        try {
            return $this->auth->getTokenByCredentials(
                credentials: $command->toArray()
            );
        }

        catch (\Throwable $e) {
            $message = trim(string: <<<MSG
                Login handler error: {$e->getMessage()}
                in {$e->getFile()}:{$e->getLine()}
            MSG);
            
            Log::error(message: $message, context: [
                'exception' => $e
            ]);

            throw new \RuntimeException(
                message: 'Login failed. Please try again',
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }
}
