<?php declare(strict_types=1);

namespace App\Account\Presentation\Responder;

use App\Shared\Presentation\Response\MessageResponse;
use Illuminate\Http\Response;

final class RegisterResponder
{
	/**
     * Generate a response based on the registration result.
     *
     * @param bool $result
     * @return MessageResponse
     */
	public function respond(bool $result): MessageResponse
	{
		if ($result) {
            return new MessageResponse(
                message: 'Registration successful!',
                status: Response::HTTP_CREATED
            );
        }

        return new MessageResponse(
            message: 'Registration failed. Try again.',
            status: Response::HTTP_INTERNAL_SERVER_ERROR
        );
	}
}
