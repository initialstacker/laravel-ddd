<?php declare(strict_types=1);

namespace App\Account\Domain\Password;

use Doctrine\ORM\Mapping as ORM;
use Illuminate\Support\Facades\Hash;

#[ORM\Embeddable]
final class Password extends HashPassword
{
    /**
     * The minimum allowed length for a plain password.
     */
    private const int MIN_LENGTH = 8;

    /**
     * The maximum allowed length for a plain password.
     */
    private const int MAX_LENGTH = 25;

    /**
     * Checks if the plain password meets length constraints.
     *
     * @param string $password
     * @return bool
     */
    public static function isValidPassword(string $password): bool
    {
        $length = mb_strlen(string: $password, encoding: 'UTF-8');
        
        return $length >= self::MIN_LENGTH && $length <= self::MAX_LENGTH;
    }

    /**
     * Instantiates from a plain password by hashing it.
     *
     * @param string $value
     * @return static
     * 
     * @throws \InvalidArgumentException
     */
    public static function fromPlain(string $value): static
    {
        if (!self::isValidPassword(password: $value)) {
            throw new \InvalidArgumentException(
                message: 'Password must be between 8 and 25 characters long.'
            );
        }

        $hash = Hash::make(value: $value);

        return new static(hash: $hash);
    }

    /**
     * Verifies if the provided plain password matches the stored hash.
     *
     * @param string $password
     * @return bool
     */
    public function verify(string $password): bool
    {
        return Hash::check(
            value: $password,
            hashedValue: $this->hash
        );
    }
}
