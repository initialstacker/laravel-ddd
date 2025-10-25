<?php declare(strict_types=1);

namespace App\Account\Application\Auth\Register;

use App\Shared\Application\Job\Job;

final class RegisterJob extends Job
{
    /**
     * Create a new registration job instance.
     *
     * @param RegisterCommand $command
     */
    public function __construct(
        private RegisterCommand $command
    ) {}

    /**
     * Handle the registration job.
     *
     * @param RegisterProcess $process
     */
    public function handle(RegisterProcess $process): void
    {
        $process->run(command: $this->command);
    }
}
