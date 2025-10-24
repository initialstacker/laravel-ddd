<?php declare(strict_types=1);

namespace App\Account\Presentation\Responder\Auth\Check;

use App\Account\Presentation\Response\AuthResponse;
use Illuminate\Http\Response;

final class CheckAuthResponder
{
    /**
     * Returns an AuthResponse based on authorization result.
     *
     * @param bool $result
     * @return AuthResponse
     */
    public function respond(bool $result): AuthResponse
    {
        if ($result) {
            return new AuthResponse(
                isAuthorized: true,
                status: Response::HTTP_OK
            );
        }
        return new ResourceResponse(
            isAuthorized: false,
            status: Response::HTTP_UNAUTHORIZED
        );
    }
}
