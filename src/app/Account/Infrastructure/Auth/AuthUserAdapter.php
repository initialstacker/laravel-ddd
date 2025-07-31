<?php declare(strict_types=1);

namespace App\Account\Infrastructure\Auth;

use App\Account\Domain\User;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

final class AuthUserAdapter implements AuthenticatableContract, JWTSubject
{
    /**
     * Provides authentication methods for the user.
     */
    use Authenticatable;

    /**
     * Create a new AuthUserAdapter instance.
     *
     * @param User $user
     */
    public function __construct(
        public private(set) User $user
    ) {}

    /**
     * Get the identifier that will be stored in the JWT subject claim.
     *
     * @return string
     */
    public function getJWTIdentifier(): string
    {
        return $this->user->id->asString();
    }

    /**
     * Get the custom claims to be added to the JWT.
     *
     * @return array<string, mixed>
     */
    public function getJWTCustomClaims(): array
    {
        return [
            'id' => $this->user->id->asString(),
            'email' => (string) $this->user->email,
        ];
    }

    /**
     * Returns the primary key name used by the model.
     *
     * @return string
     */
    public function getKeyName(): string
    {
        return 'id';
    }
}
