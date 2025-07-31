<?php declare(strict_types=1);

namespace App\Shared\Domain\Slug;

use Doctrine\ORM\Mapping as ORM;
use App\Shared\Domain\Primitive;
use Illuminate\Support\Str;

#[ORM\MappedSuperclass]
abstract class UniqueSlug extends Primitive
{
    /**
     * Property to store the slug.
     *
     * @var string
     */
    protected private(set) string $slug;

    /**
     * Constructs a new UniqueSlug instance.
     *
     * @param string $slug
     * @throws \InvalidArgumentException
     */
    public function __construct(string $slug)
    {
        if ($slug === '') {
            throw new \InvalidArgumentException(
                message: 'The provided slug is empty.'
            );
        }

        $this->slug = $slug;
    }

    /**
     * Factory method to create an instance from a slug string.
     *
     * @param string $value
     * @return static
     */
    public static function fromString(string $value): static
    {
        // @phpstan-ignore-next-line
        return new static(slug: $value);
    }

    /**
     * Checks if two slugs are equal.
     *
     * @param self $other
     * @return bool
     */
    public function equals(self $other): bool
    {
        return $this->slug === $other->slug;
    }

    /**
     * Factory method to generate a new slug from a string.
     *
     * @param string $value
     * @return static
     */
    public static function generate(string $value): static
    {
        $slug = Str::slug(
            title: $value, separator: '-', language: 'en'
        );

        // @phpstan-ignore-next-line
        return new static(slug: $slug);
    }

    /**
     * Returns the slug as a string.
     *
     * @return string
     */
    public function asString(): string
    {
        return $this->slug;
    }
}
