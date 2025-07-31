<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\Id;

use App\Shared\Domain\Id\UserId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class UserIdType extends UniqueIdType
{
    /**
     * The name of this custom Doctrine type.
     */
    public const string NAME = UserId::class;

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
        return UserId::class;
    }
}
