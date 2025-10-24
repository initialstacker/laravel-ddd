<?php declare(strict_types=1);

namespace App\Account\Domain;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\MappedSuperclass]
trait RememberToken
{
    /**
     * The token used for "remember me" functionality.
     *
     * @var string|null
     */
    #[ORM\Column(
        name: 'remember_token',
        type: Types::STRING,
        length: 100,
        unique: true,
        nullable: true
    )]
    private ?string $rememberToken = null {
        set (?string $value) {
            $rememberToken = $value !== null ? trim(
                string: $value
            ) : null;

            $this->rememberToken = $rememberToken;
        }
    }

    /**
     * Change the "remember me" token.
     *
     * @param string|null $rememberToken
     */
    public function changeRememberToken(
        ?string $rememberToken): void
    {
        $this->rememberToken = $rememberToken !== null ? trim(
            string: $rememberToken
        ) : null;
    }
}
