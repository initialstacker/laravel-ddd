<?php declare(strict_types=1);

namespace App\Account\Presentation;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

/**
 * @mixin \App\Account\Domain\User
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
        $avatar = $user->avatar;

        return [
            'id' => $user->id,
            'avatar' => $this->whenNotNull(
                value: $avatar->asString() !== '' ? $avatar : null
            ),
            'name' => $user->name,
            'email' => $user->email,
            'role' => $this->whenNotNull(
                value: $user->role !== null ? [
                    'id' => $user->role->id,
                    'name' => $user->role->name,
                    'slug' => $user->role->slug
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
