<?php declare(strict_types=1);

namespace App\Account\Application\Auth\Check\Auth;

use App\Shared\Application\Handler;
use Illuminate\Support\Facades\Log;

final class CheckAuthHandler extends Handler
{
    /**
     * Handler to verify if the user is authenticated.
     *
     * @param CheckAuthQuery $query
     * @return bool
     * 
     * @throws \RuntimeException
     */
    public function handle(CheckAuthQuery $query): bool
    {
        try {
            $auth = $query->request->user();
            $user = $auth->user;

            return $user !== null;
        }

        catch (\Throwable $e) {
            $message = trim(string: <<<MSG
                Authentication handler error: {$e->getMessage()} 
                in {$e->getFile()}:{$e->getLine()}
            MSG);

            Log::error(message: $message, context: [
                'exception' => $e
            ]);

            throw new \RuntimeException(
                message: 'Authentication failed.',
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }
}
