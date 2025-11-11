<?php declare(strict_types=1);

namespace App\Account\Application\Check\Auth;

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
     * @throws \Throwable
     */
    public function handle(CheckAuthQuery $query): bool
    {
        try {
            $auth = $query->request->user();
            $user = $auth->user;

            return $user !== null;
        }
        
        catch (\Exception $e) {
            Log::error(
                message: "Authentication check failed: {$e->getMessage()}",
                context: ['exception' => $e]
            );

            throw new \RuntimeException(
                message: 'Authentication check failed.',
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
