<?php declare(strict_types=1);

namespace App\Account\Application\Auth\Token\Issue;

use App\Shared\Application\Handler;
use App\Account\Domain\Provider\AuthProviderInterface;
use Illuminate\Support\Facades\{Auth, Log};

final class IssueTokenHandler extends Handler
{
    /**
     * Constructs a new IssueTokenHandler instance.
     *
     * @param AuthProviderInterface $auth
     */
    public function __construct(
        private readonly AuthProviderInterface $auth
    ) {}

    /**
     * Issue a new JWT token for the current authenticated user.
     *
     * @param IssueTokenQuery $query
     * @return string|null
     *
     * @throws \RuntimeException
     */
    public function handle(IssueTokenCommand $command): ?string
    {
        try {
            if (Auth::user() === null) {
                throw new \RuntimeException(
                    message: 'No authenticated user found.'
                );
            }

            $token = $this->auth->issueToken(user: Auth::user());

            if ($token === null) {
                throw new \RuntimeException(
                    message: 'Failed to issue token for user.'
                );
            }

            return $token;
        }

        catch (\Throwable $e) {
            $message = trim(string: <<<MSG
                IssueTokenForUserHandler error: {$e->getMessage()}
                in {$e->getFile()}:{$e->getLine()}
            MSG);

            Log::error(message: $message, context: [
                'exception' => $e,
            ]);

            throw new \RuntimeException(
                message: 'Could not create JWT token.',
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }
}
