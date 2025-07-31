<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\Slug;

use App\Shared\Domain\Slug\RoleSlug;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class RoleSlugType extends UniqueSlugType
{
    /**
     * The name of this custom Doctrine type.
     */
    public const string NAME = RoleSlug::class;

    /**
     * Get the name of this custom type.
     *
     * @return string
     */
    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * Get the FQCN of the Value Object this type maps.
     *
     * @return string
     */
    public function getValueObjectClass(): string
    {
        return RoleSlug::class;
    }
}
