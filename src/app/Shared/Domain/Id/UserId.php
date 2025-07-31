<?php declare(strict_types=1);

namespace App\Shared\Domain\Id;

final class UserId extends UniqueId
{
    /**
     * Returns the identifier as a string.
     *
     * @return string
     */
    public function toString(): string
    {
        return $this->id;
    }
}
