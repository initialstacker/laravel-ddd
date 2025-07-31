<?php declare(strict_types=1);

namespace App\Account\Domain\Provider;

interface AuthProviderInterface
{
	/**
     * Retrieves a token based on given credentials.
     *
     * @param array<string, string> $credentials
     * @return array<string, string>|null 
     */
    public function getTokenByCredentials(array $credentials): ?array;

    /**
     * Invalidate the current authentication token (logout).
     *
     * @return bool
     */
    public function invalidateToken(): bool;
}
