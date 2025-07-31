<?php declare(strict_types=1);

namespace App\Shared\Application\Command;

use App\Shared\Domain\Bus\CommandBusInterface;
use Illuminate\Contracts\Bus\Dispatcher;

final class CommandBus implements CommandBusInterface
{
    /**
     * Constructs a new CommandBus instance.
     *
     * @param Dispatcher $commandBus
     */
    public function __construct(
        private Dispatcher $commandBus
    ) {}

    /**
     * Dispatches a command and returns the result.
     *
     * @param object $command
     * @return mixed|null
     */
    public function send(object $command): mixed
    {
        return $this->commandBus->dispatch(
            command: $command
        );
    }

    /**
     * Registers a mapping of commands to their handlers.
     *
     * @param array<string, string> $map
     */
    public function register(array $map): void
    {
        $this->commandBus->map(map: $map);
    }
}
