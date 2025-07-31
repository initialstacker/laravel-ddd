<?php declare(strict_types=1);

namespace App\Account\Presentation\Responder\Profile;

use App\Shared\Presentation\Response\MessageResponse;
use Illuminate\Http\Response;

final class DeleteProfileResponder
{
    /**
     * Returns a MessageResponse based on delete success.
     *
     * @param bool $result
     * @return MessageResponse
     */
    public function respond(bool $result): MessageResponse
    {
        if ($result) {
            return new MessageResponse(
                message: 'Profile deleted successfully!',
                status: Response::HTTP_OK
            );
        }

        return new MessageResponse(
            message: 'Failed to delete profile.',
            status: Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
