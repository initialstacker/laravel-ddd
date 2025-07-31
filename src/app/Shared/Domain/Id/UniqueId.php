<?php declare(strict_types=1);

namespace App\Shared\Domain\Id;

use Doctrine\ORM\Mapping as ORM;
use App\Shared\Domain\Primitive;
use Illuminate\Support\Str;

#[ORM\MappedSuperclass]
abstract class UniqueId extends Primitive
{
    /**
     * Property to store the identifier.
     *
     * @var string
     */
    protected private(set) string $id;

    /**
     * Constructs a new UniqueId instance.
     *
     * @param string $id
     * @throws \InvalidArgumentException
     */
    public function __construct(string $id)
    {
        if (!Str::isUuid($id)) {
            throw new \InvalidArgumentException(
                message: 'The Provided UUID Is Invalid.'
            );
        }

        $this->id = $id;
    }

    /**
     * Factory method to create an instance from a UUID string.
     *
     * @param string $value
     * @return static
     */
    public static function fromString(string $value): static
    {
        // @phpstan-ignore-next-line
        return new static(id: $value);
    }

    /**
     * Checks if two identifiers are equal.
     *
     * @param self $other
     * @return bool
     */
    public function equals(self $other): bool
    {
        return $this->id === $other->id;
    }

    /**
     * Factory method to generate a new unique identifier.
     *
     * @return static
     */
    public static function generate(): static
    {
        // @phpstan-ignore-next-line
        return new static(id: Str::uuid()->toString());
    }

    /**
     * Returns the identifier as a string.
     *
     * @return string
     */
    public function asString(): string
    {
        return $this->id;
    }
}
