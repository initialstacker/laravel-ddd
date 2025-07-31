<?php declare(strict_types=1);

namespace App\Account\Presentation;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\{JsonResponse, Response};

final class TokenResponse implements Responsable
{
    /**
     * Constructs a new TokenResponse instance.
     *
     * @param AuthTokenData $data
     * @param ResponseMetadata $metadata
     * @param int $status
     */
    public function __construct(
        private AuthTokenData $data,
        private ResponseMetadata $metadata,
        private int $status = Response::HTTP_OK
    ) {}

    /**
     * Converts the response to a JSON response.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toResponse($request): JsonResponse
    {
        return new JsonResponse(
            data: [
                'status' => $this->status,
                'data' => $this->data->toArray(),
                'metadata' => $this->metadata->toArray(),
            ],
            status: $this->status
        );
    }
}
