<?php declare(strict_types=1);

namespace App\Account\Infrastructure\Dispatching;

use Illuminate\Support\ServiceProvider;
use App\Account\Application\Register\RegisterCommand;
use App\Account\Application\Register\RegisterProcess;
use App\Account\Application\Login\LoginCommand;
use App\Account\Application\Login\LoginHandler;
use App\Account\Application\Profile\Update\UpdateProfileCommand;
use App\Account\Application\Profile\Update\UpdateProfileHandler;
use App\Shared\Domain\Bus\CommandBusInterface;

final class CommandDispatcher extends ServiceProvider
{
    /**
     * Auth related command handlers
     * 
     * @var array<class-string, class-string>
     */
    private array $auth = [
        RegisterCommand::class => RegisterProcess::class,
        LoginCommand::class => LoginHandler::class,
    ];
    
    /**
     * Profile related command handlers
     * 
     * @var array<class-string, class-string>
     */
    private array $profile = [
        UpdateProfileCommand::class => UpdateProfileHandler::class,
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot(CommandBusInterface $commandBus): void
    {
        $commandBus->register(map: [
            ...$this->auth,
            ...$this->profile
        ]);
    }
}
