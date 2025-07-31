<?php declare(strict_types=1);

namespace App\Account\Presentation;

final class AuthTokenData
{
    /**
     * Default expiration time for access token in seconds (1 hour).
     */
    private const int DEFAULT_EXPIRES_IN = 3600;

    /**
     * Default expiration time for refresh token in seconds (60 days).
     */
    private const int DEFAULT_REFRESH_EXPIRES_IN = 5184000;

    /**
     * Constructs a new Token instance.
     *
     * @param string|null $accessToken
     * @param string|null $refreshToken
     * @param string|null $message
     */
    public function __construct(
        private ?string $accessToken = null,
        private ?string $refreshToken = null,
        private ?string $message = null,
    ) {}

    /**
     * Convert the token data to an array representation.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $hasAccessToken = $this->accessToken !== null;
        $hasRefreshToken = $this->refreshToken !== null;

        if ($hasAccessToken && $hasRefreshToken) {
            return [
                'accessToken' => [
                    'value' => $this->accessToken,
                    'expiresIn' => self::DEFAULT_EXPIRES_IN,
                ],
                'refreshToken' => [
                    'value' => $this->refreshToken,
                    'expiresIn' => self::DEFAULT_REFRESH_EXPIRES_IN,
                ],
                'tokenType' => 'bearer',
            ];
        }

        if (isset($this->message) && $this->message !== '') {
            return ['message' => $this->message];
        }

        return [];
    }
}
