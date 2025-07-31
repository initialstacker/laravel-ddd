<?php declare(strict_types=1);

namespace App\Account\Infrastructure\Auth;

use App\Account\Domain\Email\Email;
use App\Account\Domain\Provider\AuthProviderInterface;
use App\Account\Domain\Repository\UserRepositoryInterface;
use App\Shared\Domain\Id\UserId;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;

final class Authenticate implements AuthProviderInterface, UserProvider
{
    /**
     * Provides JWT authentication token management methods
     */
    use JwtAuthToken;
    
    /**
     * Repository responsible for user retrieval and storage.
     *
     * @param UserRepositoryInterface $repository
     */
    public function __construct(
        private UserRepositoryInterface $repository
    ) {}

    /**
     * Retrieve a user by their unique identifier.
     * 
     * @param string $identifier
     * @return Authenticatable|null
     */
    public function retrieveById($identifier): ?Authenticatable
    {
        if (!is_string(value: $identifier)) {
            return null;
        }

        $user = $this->repository->findById(
            id: UserId::fromString(value: $identifier)
        );

        return $user !== null ? new AuthUserAdapter(user: $user) : null;
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     * 
     * @param string $identifier
     * @param string $token
     * 
     * @return Authenticatable|null
     */
    public function retrieveByToken($identifier, $token): ?Authenticatable
    {
        if (!is_string(value: $identifier)) {
            return null;
        }

        $user = $this->repository->findByToken(
            id: UserId::fromString(value: $identifier),
            token: $token
        );

        return $user !== null ? new AuthUserAdapter(user: $user) : null;
    }

    /**
     * Update the "remember me" token for the given user.
     * 
     * @param Authenticatable $auth
     * @param string $token
     */
    public function updateRememberToken(Authenticatable $auth, $token): void
    {
        if (!$auth instanceof AuthUserAdapter) {
            return;
        }

        $auth->setRememberToken(value: $token);
        $this->repository->save(user: $auth->user);
    }

    /**
     * Retrieve a user by the given credentials.
     * 
     * @param array<string, mixed> $credentials
     * @return Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials): ?Authenticatable
    {
        $email = data_get(target: $credentials, key: 'email', default: '');

        if (!is_string(value: $email)) {
            return null;
        }

        $user = $this->repository->findByEmail(
            email: Email::fromString(value: $email)
        );

        return $user !== null ? new AuthUserAdapter(user: $user) : null;
    }

    /**
     * Validate a user's credentials.
     * 
     * @param Authenticatable $auth
     * @param array<string, mixed> $credentials
     * 
     * @return bool
     */
    public function validateCredentials(
        Authenticatable $auth, array $credentials): bool
    {
        if (!$auth instanceof AuthUserAdapter) {
            return false;
        }

        $password = data_get(target: $credentials, key: 'password', default: '');

        if (!is_string(value: $password)) {
            return false;
        }

        return Hash::check(
            value: $password,
            hashedValue: (string) $auth->user->password
        );
    }
    
    /**
     * Rehash the user's password if needed or forced.
     * 
     * @param Authenticatable $auth
     * @param array<string, mixed> $credentials
     * @param bool $force
     */
    public function rehashPasswordIfRequired(
        Authenticatable $auth,
        array $credentials,
        bool $force = false
    ): void {
        if (!$auth instanceof AuthUserAdapter) {
            return;
        }

        $password = data_get(target: $credentials, key: 'password', default: '');
        if (!is_string($password)) {
            return;
        }

        $isRehashNeeded = Hash::needsRehash(
            hashedValue: (string) $auth->user->password
        );

        if ($isRehashNeeded || $force) {
            $auth->user->changePassword(
                password: Hash::make(value: $password)
            );

            $this->repository->save(user: $auth->user);
        }
    }

    /**
     * Get JWT token by user credentials.
     * 
     * @param array<string, mixed> $credentials
     * @return array<string, string>|null
     */
    public function getTokenByCredentials(array $credentials): ?array
    {
        $user = $this->retrieveByCredentials(credentials: $credentials);
        if ($user === null) {
            return null;
        }

        if (!$this->validateCredentials(
            auth: $user, credentials: $credentials)) {
            return null;
        }

        $this->rehashPasswordIfRequired(
            auth: $user, credentials: $credentials);

        $rememberToken = bin2hex(string: random_bytes(length: 30));
        $this->updateRememberToken(auth: $user, token: $rememberToken);

        $accessToken = $this->issueToken(user: $user);
        $refreshToken = $this->issueRefreshToken(user: $user);

        if (!$accessToken || !$refreshToken) {
            return null;
        }

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
        ];
    }
}
