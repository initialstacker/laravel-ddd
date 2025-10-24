<?php declare(strict_types=1);

namespace App\Shared\Application\Job;

use Illuminate\Support\Facades\Bus;

abstract class JobChain
{
    /**
     * Array of jobs to run in sequence.
     *
     * @var array<int, object|string|callable>
     */
    protected array $jobs = [];

    /**
     * Run the chain of podcast jobs.
     *
     * @return mixed
     */
    public function run(): mixed
    {
        return Bus::chain(
            command: $this->jobs
        )->dispatch();
    }
}
