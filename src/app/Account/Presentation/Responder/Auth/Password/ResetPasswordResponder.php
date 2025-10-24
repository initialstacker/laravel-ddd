<?php declare(strict_types=1);

namespace App\Account\Presentation\Responder\Auth\Password;

use App\Shared\Presentation\Response\MessageResponse;
use Illuminate\Http\Response;

final class ResetPasswordResponder
{
    /**
     * Returns response for reset password result.
     *
     * @param bool $result
     * @return MessageResponse
     */
    public function respond(bool $result): MessageResponse
    {
        if ($result) {
            return new MessageResponse(
                message: 'Password has been reset successfully!',
                status: Response::HTTP_OK
            );
        }

        return new MessageResponse(
            message: 'Failed to reset the password.',
            status: Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
