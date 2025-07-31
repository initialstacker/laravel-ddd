<?php declare(strict_types=1);

namespace App\Shared\Domain\Bus;

interface EventBusInterface
{
    /**
     * Dispatches an event and calls the listeners.
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
    ): ?array;

    /**
     * Register an event subscriber.
     *
     * @param object|string $subscriber
     */
    public function subscribe(object|string $subscriber): void;

    /**
     * Registers an event listener.
     *
     * @param string|array<string>|(\Closure) $events
     * @param string|array<string>|(\Closure)|null $listener
     */
    public function listen(
        string|array|\Closure $events,
        string|array|\Closure|null $listener = null
    ): void;

    /**
     * Checks if there are listeners for the given event.
     *
     * @param string $eventName
     * @return bool
     */
    public function hasListeners(string $eventName): bool;
}
