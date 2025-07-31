<?php declare(strict_types=1);

namespace App\Shared\Domain;

use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
abstract class AggregateRoot
{
    /**
     * Collection of recorded domain events.
     * 
     * @var Event[] 
     */
    private array $events = [];

    /**
     * Records a domain event for later dispatch.
     * 
     * @param Event $event
     */
    public function record(Event $event): void
    {
        $this->events[] = $event;
    }

    /**
     * Releases and clears all recorded domain events.
     * 
     * @return Event[]
     */
    public function release(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }
}
