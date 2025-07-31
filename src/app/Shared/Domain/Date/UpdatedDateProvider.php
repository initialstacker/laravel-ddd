<?php declare(strict_types=1);

namespace App\Shared\Domain\Date;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use Carbon\CarbonImmutable;

#[ORM\HasLifecycleCallbacks]
trait UpdatedDateProvider
{
    /**
     * Timestamp when was last updated.
     *
     * @var \DateTimeImmutable
     */
    #[Assert\NotNull(message: 'Updated at must not be null.')]
    #[ORM\Column(name: 'updated_at', type: Types::DATETIME_IMMUTABLE, options: [
        'precision' => 6,
    ], nullable: true)]
    public private(set) ?\DateTimeImmutable $updatedAt = null;

    /**
     * Updates the modification timestamp before persistence.
     */
    #[ORM\PreUpdate]
    public function initializeUpdatedAt(): void
    {
        $this->updatedAt = CarbonImmutable::now();
    }
}
