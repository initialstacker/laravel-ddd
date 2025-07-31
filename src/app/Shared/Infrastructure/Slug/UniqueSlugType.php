<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\Slug;

use Doctrine\DBAL\Types\Types;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use App\Shared\Domain\Slug\UniqueSlug;

abstract class UniqueSlugType extends Type
{
    /**
     * Get the fully qualified class name of the Value Object.
     *
     * @return string
     */
    abstract public function getValueObjectClass(): string;

    /**
     * Get the SQL declaration for the string type.
     *
     * @param array<string, mixed> $fieldDeclaration
     * @param AbstractPlatform $platform
     *
     * @return string
     */
    public function getSQLDeclaration(
        array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return Types::STRING;
    }

    /**
     * Convert a database value to a PHP Value Object.
     *
     * @param mixed $value
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform
     *
     * @return UniqueSlug|null
     */
    public function convertToPHPValue(
        mixed $value, AbstractPlatform $platform): ?UniqueSlug
    {
        if ($value === null) {
            return null;
        }
        
        $valueObjectClass = $this->getValueObjectClass();

        /** @var UniqueSlug $valueObject */
        $valueObject = new $valueObjectClass($value);

        return $valueObject;
    }

    /**
     * Convert a PHP Value Object to a database value.
     *
     * @param mixed $value
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform
     *
     * @return string|null
     */
    public function convertToDatabaseValue(
        mixed $value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof UniqueSlug) {
            return (string) $value;
        }
        
        return null;
    }
}
