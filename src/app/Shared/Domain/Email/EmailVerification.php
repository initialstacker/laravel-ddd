<?php declare(strict_types=1);

namespace App\Shared\Domain\Email;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Carbon\CarbonImmutable;

#[ORM\MappedSuperclass]
trait EmailVerification
{
    /**
     * The timestamp when the email was verified.
     *
     * @var \DateTimeImmutable|null
     */
    #[ORM\Column(
        name: 'email_verified_at',
        type: Types::DATETIME_IMMUTABLE,
        nullable: true
    )]
    private ?\DateTimeImmutable $emailVerifiedAt = null;

    /**
     * Reset email verification status.
     */
    public function resetEmailVerification(): void
    {
        $this->emailVerifiedAt = null;
    }

    /**
     * Mark the email as verified with current timestamp.
     */
    public function verifyEmail(): void
    {
        $this->emailVerifiedAt = CarbonImmutable::now();
    }

    /**
     * Check if the email has been verified.
     *
     * @return bool
     */
    public function isEmailVerified(): bool
    {
        return $this->emailVerifiedAt !== null;
    }
}
