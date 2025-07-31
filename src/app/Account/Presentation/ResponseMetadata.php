<?php declare(strict_types=1);

namespace App\Account\Presentation;

use Illuminate\Support\Facades\Context;

final class ResponseMetadata
{
    /**
     * Converts the response metadata to an associative array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'request_id' => Context::get(
                key: 'request_id'
            ),
            'timestamp' => Context::get(
                key: 'timestamp'
            ),
        ];
    }
}
