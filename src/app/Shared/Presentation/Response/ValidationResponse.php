<?php declare(strict_types=1);

namespace App\Shared\Presentation\Response;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\MessageBag;
use Illuminate\Http\{JsonResponse, Response};
use Illuminate\Support\Facades\Context;

final class ValidationResponse implements Responsable
{
    /**
     * Constructs a new ValidationResponse instance.
     *
     * @param MessageBag $errors
     */
    public function __construct(
        private readonly MessageBag $errors
    ) {}

    /**
     * Converts the response to a JSON response.
     *
     * @param mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toResponse($request): JsonResponse
    {
        $requestId = Context::get(key: 'request_id');
        $timestamp = Context::get(key: 'timestamp');

        return new JsonResponse(
            data: [
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'result' => [
                    'message' => __(key: 'Validation error.'),
                    'errors' => $this->errors
                ],
                'metadata' => [
                    'request_id' => $requestId,
                    'timestamp' => $timestamp,
                ],
            ],
            status: Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
