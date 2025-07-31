<?php declare(strict_types=1);

namespace App\Account\Presentation\Responder\Profile;

use App\Shared\Presentation\Response\MessageResponse;
use Illuminate\Http\Response;

final class UpdateProfileResponder
{
    /**
     * Returns a MessageResponse based on update success.
     *
     * @param bool $result
     * @return MessageResponse
     */
    public function respond(bool $result): MessageResponse
    {
        if ($result) {
            return new MessageResponse(
                message: 'Profile updated successfully!',
                status: Response::HTTP_OK
            );
        }

        return new MessageResponse(
            message: 'Failed to update profile.',
            status: Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
