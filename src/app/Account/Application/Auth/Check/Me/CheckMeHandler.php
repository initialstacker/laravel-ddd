<?php declare(strict_types=1);

namespace App\Account\Application\Auth\Check\Me;

use App\Shared\Application\Handler;
use App\Account\Domain\User;
use Illuminate\Support\Facades\Log;

final class CheckMeHandler extends Handler
{
    /**
     * Handler to retrieve the currently authenticated user.
     *
     * @param CheckMeQuery $query
     * @return User|null
     *
     * @throws \RuntimeException
     */
    public function handle(CheckMeQuery $query): ?User
    {
        try {
            $auth = $query->request->user();
            $user = $auth->user;

            return $user ?? null;
        }

        catch (\Throwable $e) {
            $message = trim(string: <<<MSG
                CheckMe handler error: {$e->getMessage()}
                in {$e->getFile()}:{$e->getLine()}
            MSG);

            Log::error(message: $message, context: [
                'exception' => $e
            ]);

            throw new \RuntimeException(
                message: 'Failed to retrieve authenticated user.',
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }
}
