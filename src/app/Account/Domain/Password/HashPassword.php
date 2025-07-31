<?php declare(strict_types=1);

namespace App\Account\Domain\Password;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Shared\Domain\Primitive;

#[ORM\MappedSuperclass]
class HashPassword extends Primitive
{
    /**
     * The hashed password string stored in the database.
     *
     * @var string
     */
    #[Assert\NotBlank(message: 'Password hash must not be empty.')]
    #[Assert\Length(
        min: 60,
        max: 60,
        exactMessage: 'Password hash must be exactly {{ limit }} characters long.'
    )]
    #[ORM\Column(name: 'password', type: 'string', length: 60)]
    protected string $hash;

    /**
     * Initializes a new HashPassword value object.
     *
     * @param string $hash
     * 
     * @throws \InvalidArgumentException
     */
    public function __construct(string $hash)
    {
        if (!$this->isValidHash(hash: $hash)) {
            throw new \InvalidArgumentException(
                message: 'Password hash must be exactly 60 characters long.'
            );
        }

        $this->hash = $hash;
    }

    /**
     * Validates the password hash format.
     *
     * @param string $hash
     * @return bool
     */
    protected function isValidHash(string $hash): bool
    {
        return mb_strlen(string: $hash, encoding: 'UTF-8') === 60;
    }

    /**
     * Compares this HashPassword object with another for equality.
     *
     * @param self $other
     * @return bool
     */
    public function equals(self $other): bool
    {
        return hash_equals(
            known_string: $this->hash,
            user_string: $other->hash
        );
    }

    /**
     * Returns the hashed password as a string.
     *
     * @return string
     */
    public function asString(): string
    {
        return $this->hash;
    }
}
