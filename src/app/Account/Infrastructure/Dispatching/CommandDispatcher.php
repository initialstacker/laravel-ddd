<?php declare(strict_types=1);

namespace App\Account\Infrastructure\Dispatching;

use Illuminate\Support\ServiceProvider;
use App\Account\Application\Auth\Login\LoginCommand;
use App\Account\Application\Auth\Login\LoginHandler;
use App\Account\Application\Auth\Register\RegisterCommand;
use App\Account\Application\Auth\Register\RegisterProcess;
use App\Account\Application\Auth\Logout\LogoutCommand;
use App\Account\Application\Auth\Logout\LogoutHandler;
use App\Account\Application\Auth\Token\Issue\IssueTokenCommand;
use App\Account\Application\Auth\Token\Issue\IssueTokenHandler;
use App\Account\Application\Auth\Token\Refresh\RefreshTokenCommand;
use App\Account\Application\Auth\Token\Refresh\RefreshTokenHandler;
use App\Account\Application\Auth\Password\Forgot\ForgotPasswordCommand;
use App\Account\Application\Auth\Password\Forgot\ForgotPasswordHandler;
use App\Account\Application\Auth\Password\Reset\ResetPasswordCommand;
use App\Account\Application\Auth\Password\Reset\ResetPasswordHandler;
use App\Account\Application\Profile\Update\UpdateProfileCommand;
use App\Account\Application\Profile\Update\UpdateProfileHandler;
use App\Account\Application\Profile\Delete\DeleteProfileCommand;
use App\Account\Application\Profile\Delete\DeleteProfileHandler;
use App\Account\Application\Role\Create\CreateRoleCommand;
use App\Account\Application\Role\Create\CreateRoleHandler;
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
        ResetPasswordCommand::class => ResetPasswordHandler::class,
    ];
    
    /**
     * Profile related command handlers
     * 
     * @var array<class-string, class-string>
     */
    private array $profile = [
        UpdateProfileCommand::class => UpdateProfileHandler::class,
        DeleteProfileCommand::class => DeleteProfileHandler::class,
    ];
    
    /**
     * Role related command handlers
     * 
     * @var array<class-string, class-string>
     */
    private array $role = [
        CreateRoleCommand::class => CreateRoleHandler::class,
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot(CommandBusInterface $commandBus): void
    {
        $commandBus->register(map: [
            ...$this->auth,
            ...$this->profile,
            ...$this->role
        ]);
    }
}
