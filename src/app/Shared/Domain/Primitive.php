<?php declare(strict_types=1);

namespace App\Shared\Domain;

abstract class Primitive implements \JsonSerializable
{
    /**
     * Returns the string representation of the Primitive.
     *
     * @return string
     */
    abstract public function asString(): string;
    
    /**
     * Serializes the Primitive to JSON format.
     *
     * @return string
     */
    public function jsonSerialize(): string
    {
        return $this->asString();
    }

    /**
     * Magic method to convert the Primitive to string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->asString();
    }
}
