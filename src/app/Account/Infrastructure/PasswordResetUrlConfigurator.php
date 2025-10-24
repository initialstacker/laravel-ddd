<?php declare(strict_types=1);

namespace App\Account\Infrastructure;

use Illuminate\Support\ServiceProvider;
use App\Account\Infrastructure\Auth\AuthUserAdapter;
use Illuminate\Auth\Notifications\ResetPassword;

final class PasswordResetUrlConfigurator extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(
            callback: function (AuthUserAdapter $user, string $token): string {
                $params = "token={$token}&email={$user->getEmailForPasswordReset()}";
                return config(key: 'app.url') . "/password/reset?{$params}";
            }
        );
    }
}
