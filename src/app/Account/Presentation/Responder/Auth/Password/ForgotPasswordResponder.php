<?php declare(strict_types=1);

namespace App\Account\Presentation\Responder\Auth\Password;

use App\Shared\Presentation\Response\MessageResponse;
use Illuminate\Http\Response;

final class ForgotPasswordResponder
{
    /**
     * Generate a response based on the forgot password process result.
     *
     * @param bool $result
     * @return MessageResponse
     */
    public function respond(bool $result): MessageResponse
    {
        if ($result) {
            return new MessageResponse(
                message: 'Password reset instructions sent.',
                status: Response::HTTP_OK
            );
        }

        return new MessageResponse(
            message: 'Password reset failed. Try again later.',
            status: Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
