<?php declare(strict_types=1);

namespace App\Account\Application\Auth\Register;

use App\Shared\Application\Process;
use App\Account\Application\Auth\Register\Handler\AttachDefaultRoleHandler;
use App\Account\Application\Auth\Register\Handler\RegisterUserHandler;

final class RegisterProcess extends Process
{
	/**
     * List of process handlers to be executed in order.
     *
     * @var array<int, class-string>
     */
    protected array $handlers = [
        AttachDefaultRoleHandler::class,
        RegisterUserHandler::class
    ];

    /**
     * Executes the registration process pipeline.
     *
     * @param RegisterCommand $command
     * @return bool
     *
     * @throws \Throwable
     */
    public function __invoke(RegisterCommand $command): bool
    {
        try {
            $this->run(command: $command);

            return true;
        }

        catch (\Throwable $e) {
            return false;
        }
    }
}
