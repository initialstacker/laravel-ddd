<?php declare(strict_types=1);

namespace App\Account\Application\Password\Forgot;

use App\Shared\Application\Handler;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;

final class ForgotPasswordHandler extends Handler
{
    /**
     * Handles sending a password reset link to the user's email.
     *
     * @param ForgotPasswordCommand $command
     * @return bool
     * 
     * @throws \RuntimeException
     * @throws \Throwable
     */
    public function handle(ForgotPasswordCommand $command): bool
    {
        try {
            $status = Password::sendResetLink(
                credentials: ['email' => $command->email]
            );

            return $status === Password::RESET_LINK_SENT;
        }
        
        catch (\Exception $e) {
            Log::error(
                message: "Failed to send password reset link: {$e->getMessage()}",
                context: ['exception' => $e]
            );

            throw new \RuntimeException(
                message: 'Failed to send password reset link.',
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
