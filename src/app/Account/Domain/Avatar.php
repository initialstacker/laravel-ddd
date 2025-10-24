<?php declare(strict_types=1);

namespace App\Account\Domain;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use App\Shared\Domain\Primitive;

#[ORM\Embeddable]
final class Avatar extends Primitive
{
    /**
     * The avatar file name or path.
     *
     * @var string|null
     */
    #[ORM\Column(name: 'avatar', type: Types::STRING, length: 255, nullable: true)]
    private ?string $avatar = null;
    /**
     * @param string|null $avatar
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(?string $avatar)
    {
        if ($avatar !== null) {
            $avatar = trim(string: $avatar);
            if (mb_strlen(string: $avatar) > 255) {
                $message = sprintf('Avatar path exceeds 255 characters: "%s"', $avatar);
                throw new \InvalidArgumentException(message: $message);
            }
        }
        $this->avatar = $avatar;
    }
    /**
     * Creates a Avatar object from a string or null.
     *
     * @param string|null $value
     * @return self
     *
     * @throws \InvalidArgumentException
     */
    public static function fromString(?string $value): self
    {
        return new self(avatar: $value);
    }
    /**
     * Compares this Avatar object to another.
     *
     * @param self $other
     * @return bool
     */
    public function equals(self $other): bool
    {
        return $this->avatar === $other->avatar;
    }
    /**
     * Returns the avatar path or filename, or an empty string if null.
     *
     * @return string
     */
    public function asString(): string
    {
        return $this->avatar ?? '';
    }
}
