<?php declare(strict_types=1);

namespace App\Account\Application\Check\Me;

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
     * @throws \Throwable
     */
    public function handle(CheckMeQuery $query): ?User
    {
        try {
            $auth = $query->request->user();
            $user = $auth->user;

            return $user ?? null;
        }
        
        catch (\Exception $e) {
            Log::error(
                message: "Failed to get authenticated user: {$e->getMessage()}",
                context: ['exception' => $e]
            );

            throw new \RuntimeException(
                message: 'Failed to get authenticated user.',
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
