<?php declare(strict_types=1);

namespace App\Shared\Application;

use App\Shared\Domain\Bus\EventBusInterface;
use Illuminate\Contracts\Events\Dispatcher;

final class EventBus implements EventBusInterface
{
    /**
     * Creates a new EventBus instance.
     *
     * @param Dispatcher $dispatcher
     */
    public function __construct(
        private Dispatcher $dispatcher
    ) {}

    /**
     * Dispatches an event and calls its listeners.
     *
     * @param string|object $event
     * @param mixed $payload
     * @param bool $halt
     * 
     * @return array<mixed,mixed>|null
     */
    public function dispatch(
        string|object $event,
        mixed $payload = [],
        bool $halt = false
    ): ?array
    {
        return $this->dispatcher->dispatch(
            event: $event,
            payload: $payload,
            halt: $halt
        );
    }

    /**
     * Register an event subscriber with the dispatcher.
     *
     * @param object|string $subscriber
     */
    public function subscribe(object|string $subscriber): void
    {
        $this->dispatcher->subscribe(
            subscriber: $subscriber
        );
    }

    /**
     * Registers an event listener with the dispatcher.
     *
     * @param string|array<string>|(\Closure) $events
     * @param string|array<string>|(\Closure)|null $listener
     */
    public function listen(
        string|array|\Closure $events,
        string|array|\Closure|null $listener = null
    ): void
    {
        $this->dispatcher->listen(
            events: $events,
            listener: $listener
        );
    }

    /**
     * Checks if listeners exist for the event.
     *
     * @param string $eventName
     * @return bool
     */
    public function hasListeners(string $eventName): bool
    {
        return $this->dispatcher->hasListeners(
            eventName: $eventName
        );
    }
}
