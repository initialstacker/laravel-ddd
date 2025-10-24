<?php declare(strict_types=1);

namespace App\Account\Presentation\Response;

use Illuminate\Contracts\Support\Responsable;
use App\Account\Presentation\ResponseMetadata;
use Illuminate\Support\Facades\Context;
use Illuminate\Http\{JsonResponse, Response};

final class AuthResponse implements Responsable
{
    /**
     * Response metadata providing additional context.
     *
     * @var ResponseMetadata
     */
    private ResponseMetadata $metadata;

    /**
     * Constructs a new AuthResponse instance.
     *
     * @param bool $isAuthorized
     * @param int $status
     */
    public function __construct(
        private bool $isAuthorized,
        private int $status = Response::HTTP_OK
    ) {
        $this->metadata = new ResponseMetadata();
    }

    /**
     * Converts the response to a JSON response.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function toResponse($request): JsonResponse
    {
        $requestId = Context::get(key: 'request_id');
        $timestamp = Context::get(key: 'timestamp');

        return new JsonResponse(
            data: [
                'status' => $this->status,
                'data' => [
                    'authenticated' => $this->isAuthorized
                ],
                'metadata' => $this->metadata->toArray()
            ],
            status: $this->status
        );
    }
}
