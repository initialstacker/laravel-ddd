<?php declare(strict_types=1);

namespace App\Shared\Application;

use Illuminate\Pipeline\Pipeline;
use App\Shared\Application\Command\Command;

abstract class Process
{
    /**
     * Array of pipeline handlers (pipes).
     *
     * @var array<int, string|callable|object>
     */
    protected array $handlers = [];

    /**
     * Executes the Command through the pipeline.
     *
     * @param Command $command
     * @return mixed
     */
    public function run(Command $command): mixed
    {
        return app(
            abstract: Pipeline::class
        )->send(
            passable: $command
        )->through(
            pipes: $this->handlers
        )->thenReturn();
    }
}
