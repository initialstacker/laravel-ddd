<?php declare(strict_types=1);

namespace App\Account\Presentation\Responder\Auth;

use App\Shared\Presentation\Response\MessageResponse;
use Illuminate\Http\Response;

final class LogoutResponder
{
	/**
     * Generate a response based on the logout result.
     *
     * @param bool $result
     * @return MessageResponse
     */
    public function respond(bool $result): MessageResponse
    {
        if ($result) {
            return new MessageResponse(
                message: 'You have been logged out successfully!',
                status: Response::HTTP_OK
            );
        }

        return new MessageResponse(
            message: 'Logout failed. Please try again.',
            status: Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
