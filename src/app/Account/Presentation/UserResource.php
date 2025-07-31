<?php declare(strict_types=1);

namespace App\Account\Presentation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property \App\Account\Domain\User $resource
 */
final class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $this->resource;

        return [
            'id' => $user->id->asString(),
            'name' => $user->name,
            'email' => $user->email->asString(),
            'role' => $this->whenNotNull(
                value: $user->role !== null ? [
                    'id' => $user->role->id->asString(),
                    'name' => $user->role->name,
                    'slug' => $user->role->slug->asString()
                ] : null
            ),
            'datetime' => [
                'created_at' => $user->createdAt?->format(
                    format: 'Y-m-d H:i:s'
                ),
                'updated_at' => $user->updatedAt?->format(
                    format: 'Y-m-d H:i:s'
                ),
            ],
        ];
    }
}
