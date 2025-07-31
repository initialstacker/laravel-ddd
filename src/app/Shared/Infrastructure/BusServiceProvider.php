<?php declare(strict_types=1);

namespace App\Shared\Infrastructure;

use Illuminate\Support\ServiceProvider;

final class BusServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            abstract: \App\Shared\Domain\Bus\CommandBusInterface::class,
            concrete: \App\Shared\Application\Command\CommandBus::class
        );

        $this->app->singleton(
            abstract: \App\Shared\Domain\Bus\QueryBusInterface::class,
            concrete: \App\Shared\Application\Query\QueryBus::class
        );

        $this->app->singleton(
            abstract: \App\Shared\Domain\Bus\EventBusInterface::class,
            concrete: \App\Shared\Application\EventBus::class
        );
    }
}
