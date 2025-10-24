<?php declare(strict_types=1);

namespace App\Account\Presentation\Responder\Auth\Check;

use App\Account\Domain\User;
use App\Account\Presentation\UserResource;
use App\Shared\Presentation\Response\ResourceResponse;
use Illuminate\Http\Response;

final class CheckMeResponder
{
    /**
     * Creates a ResourceResponse based on the given User result.
     *
     * @param User|null $result
     * @return ResourceResponse
     */
    public function respond(?User $result): ResourceResponse
    {
        if ($result instanceof User) {
            return new ResourceResponse(
                data: new UserResource(resource: $result),
                status: Response::HTTP_OK
            );
        }

        return new ResourceResponse(
            data: ['message' => 'You are not authenticated.'],
            status: Response::HTTP_UNAUTHORIZED
        );
    }
}
