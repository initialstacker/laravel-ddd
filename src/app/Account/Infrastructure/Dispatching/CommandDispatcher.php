<?php declare(strict_types=1);

namespace App\Account\Infrastructure\Dispatching;

use Illuminate\Support\ServiceProvider;
use App\Account\Application\Login\LoginCommand;
use App\Account\Application\Login\LoginHandler;
use App\Account\Application\Register\RegisterCommand;
use App\Account\Application\Register\RegisterProcess;
use App\Account\Application\Logout\LogoutCommand;
use App\Account\Application\Logout\LogoutHandler;
use App\Account\Application\Token\Issue\IssueTokenCommand;
use App\Account\Application\Token\Issue\IssueTokenHandler;
use App\Account\Application\Token\Refresh\RefreshTokenCommand;
use App\Account\Application\Token\Refresh\RefreshTokenHandler;
use App\Account\Application\Password\Forgot\ForgotPasswordCommand;
use App\Account\Application\Password\Forgot\ForgotPasswordHandler;
use App\Account\Application\Password\Reset\ResetPasswordCommand;
use App\Account\Application\Password\Reset\ResetPasswordHandler;
use App\Account\Application\Profile\Update\UpdateProfileCommand;
use App\Account\Application\Profile\Update\UpdateProfileHandler;
use App\Account\Application\Profile\Delete\DeleteProfileCommand;
use App\Account\Application\Profile\Delete\DeleteProfileHandler;
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
        LogoutCommand::class => LogoutHandler::class,
        IssueTokenCommand::class => IssueTokenHandler::class,
        RefreshTokenCommand::class => RefreshTokenHandler::class,
        ForgotPasswordCommand::class => ForgotPasswordHandler::class,
        ResetPasswordCommand::class => ResetPasswordHandler::class
    ];
    
    /**
     * Profile related command handlers
     * 
     * @var array<class-string, class-string>
     */
    private array $profile = [
        UpdateProfileCommand::class => UpdateProfileHandler::class,
        DeleteProfileCommand::class => DeleteProfileHandler::class
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
