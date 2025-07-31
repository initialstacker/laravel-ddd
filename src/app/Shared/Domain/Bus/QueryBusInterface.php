<?php declare(strict_types=1);

namespace App\Shared\Domain\Bus;

interface QueryBusInterface
{
    /**
     * Executes a query and returns the result.
     *
     * @param object $query
     * @return mixed
     */
    public function ask(object $query): mixed;

    /**
     * Registers a mapping of queries to their handlers.
     *
     * @param array<string, string> $map
     */
    public function register(array $map): void;
}
