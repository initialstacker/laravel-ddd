<?php declare(strict_types=1);

namespace App\Account\Domain\Email;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use App\Shared\Domain\Primitive;

#[ORM\Embeddable]
final class Email extends Primitive
{
    /**
     * The email address value.
     *
     * @var string
     */
    #[Assert\NotBlank(message: 'Email must not be empty.')]
    #[Assert\Email(message: 'Email must be a valid email address.')]
    #[Assert\Length(max: 254, maxMessage: 'Email must be less than 254 characters.')]
    #[ORM\Column(name: 'email', type: Types::STRING, length: 254, unique: true)]
    private string $email;

    /**
     * Validates and normalizes the provided email address.
     *
     * @param string $email
     * 
     * @throws \InvalidArgumentException
     */
    public function __construct(string $email)
    {
        self::ensureValidEmail(email: $email);
        $this->email = mb_strtolower(string: trim(string: $email));
    }

    /**
     * Creates an Email object from a string.
     *
     * @param string $value
     * @return self
     *
     * @throws \InvalidArgumentException
     */
    public static function fromString(string $value): self
    {
        return new self(email: $value);
    }

    /**
     * Compares this Email object to another.
     *
     * @param self $other
     * @return bool
     */
    public function equals(self $other): bool
    {
        return $this->email === $other->email;
    }

    /**
     * Returns the email address as a string.
     *
     * @return string
     */
    public function asString(): string
    {
        return $this->email;
    }

    /**
     * Validates the email format and length.
     *
     * @param string $email
     * @throws \InvalidArgumentException
     */
    private static function ensureValidEmail(string $email): void
    {
        if (!(bool) filter_var(value: $email, filter: FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException(
                message: sprintf('Invalid Email Format: "%s"', $email)
            );
        }
        
        self::ensureMaxLength(email: $email);
    }

    /**
     * Ensures the email does not exceed the maximum allowed length.
     *
     * @param string $email
     * @throws \InvalidArgumentException
     */
    private static function ensureMaxLength(string $email): void
    {
        if (strlen(string: $email) > 254) {
            throw new \InvalidArgumentException(
                message: sprintf(
                    'Email length exceeds 254 characters: "%s"', $email
                )
            );
        }
    }
}
