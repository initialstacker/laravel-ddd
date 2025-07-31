<?php declare(strict_types=1);

namespace App\Account\Infrastructure\Auth;

use App\Account\Domain\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;

trait JwtAuthToken
{
    /**
     * Generate a JWT token for the given user.
     *
     * @param Authenticatable $user
     * @return string|null
     */
    public function issueToken(Authenticatable $user): ?string
    {
        try {
            $ttl = (int) config(key: 'jwt.ttl', default: 60);
            JWTAuth::factory()->setTTL(ttl: $ttl);

            return JWTAuth::fromUser(user: $user);
        }

        catch (JWTException $e) {
            return null;
        }
    }

    /**
     * Validate the given JWT token.
     *
     * @param string $token
     * @return bool
     */
    public function isTokenValid(string $token): bool
    {
        try {
            JWTAuth::setToken(token: $token)->checkOrFail();
            
            return true;
        }

        catch (JWTException $e) {
            return false;
        }
    }

    /**
     * Refresh the current token and return a new one.
     *
     * @return string|null
     */
    public function refreshToken(): ?string
    {
        try {
            $token = JWTAuth::getToken();

            if (!$token) {
                return null;
            }

            return JWTAuth::setToken(token: $token)->refresh();
        }

        catch (JWTException $e) {
            return null;
        }
    }

    /**
     * Generate a refresh token with TTL 60 days.
     *
     * @param Authenticatable $user
     * @return string|null
     */
    public function issueRefreshToken(Authenticatable $user): ?string
    {
        try {
            $refreshTtl = (int) config(
                key: 'jwt.refresh_ttl',
                default: 86400
            );

            JWTAuth::factory()->setTTL(ttl: $refreshTtl);

            return JWTAuth::claims(
                customClaims: ['typ' => 'refresh']
            )->fromUser(
                user: $user
            );
        }

        catch (JWTException $e) {
            return null;
        }
    }

    /**
     * Get the currently authenticated user.
     *
     * @return User|null
     */
    public function getCurrentUser(): ?User
    {
        return Auth::user()->user;
    }

    /**
     * Invalidate the current token (logout).
     *
     * @return bool
     */
    public function invalidateToken(): bool
    {
        try {
            $token = JWTAuth::getToken();

            if (!$token) {
                return false;
            }

            JWTAuth::setToken(token: $token)->invalidate();

            return true;
        }

        catch (JWTException $e) {
            return false;
        }
    }
}
