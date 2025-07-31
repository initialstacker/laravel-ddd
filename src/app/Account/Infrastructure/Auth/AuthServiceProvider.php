<?php declare(strict_types=1);

namespace App\Account\Infrastructure\Auth;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application as App;
use Illuminate\Support\Facades\Auth;
use App\Account\Domain\Repository\UserRepositoryInterface;
use App\Account\Domain\Provider\AuthProviderInterface;
use App\Account\Infrastructure\Auth\Authenticate;

final class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            abstract: AuthProviderInterface::class,
            concrete: Authenticate::class
        );
    }
    
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Auth::provider(name: 'doctrine',
            callback: fn (App $app, array $config): Authenticate
                => new Authenticate(
                    repository: $app->make(
                        abstract: UserRepositoryInterface::class
                    )
                )
        );
    }
}
