<?php declare(strict_types=1);

namespace App\Shared\Domain\Bus;

interface CommandBusInterface
{
    /**
     * Dispatches a command and returns the result.
     *
     * @param object $command
     * @return mixed|null
     */
    public function send(object $command): mixed;

    /**
     * Registers a mapping of commands to their handlers.
     *
     * @param array<string, string> $map
     */
    public function register(array $map): void;
}
