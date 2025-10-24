<?php declare(strict_types=1);

namespace App\Shared\Domain\Date;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Carbon\CarbonImmutable;

#[ORM\HasLifecycleCallbacks]
trait CreatedDateProvider
{
    /**
     * Timestamp when was created.
     *
     * @var \DateTimeImmutable
     */
    #[ORM\Column(name: 'created_at', type: Types::DATETIME_IMMUTABLE, options: [
        'precision' => 6,
        'default' => 'CURRENT_TIMESTAMP',
    ])]
    public private(set) ?\DateTimeImmutable $createdAt = null;

    /**
     * Initializes the createdAt timestamp before first persistence.
     */
    #[ORM\PrePersist]
    public function initializeCreatedAt(): void
    {
        if ($this->createdAt === null) {
            $this->createdAt = CarbonImmutable::now();
        }
    }
}
