<?php declare(strict_types=1);

namespace App\Account\Application\Auth\Password\Forgot;

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
     */
    public function handle(ForgotPasswordCommand $command): bool
    {
        try {
            $status = Password::sendResetLink(
                credentials: ['email' => $command->email]
            );

            return $status === Password::RESET_LINK_SENT;
        }
        
        catch (\Throwable $e) {
            $message = trim(string: <<<MSG
                Forgot password handler error: {$e->getMessage()}
                in {$e->getFile()}:{$e->getLine()}
            MSG);

            Log::error(message: $message, context: [
                'exception' => $e,
                'email' => $command->email,
            ]);

            throw new \RuntimeException(
                message: 'Failed to send password reset link.',
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }
}
