<?php declare(strict_types=1);

namespace Initialstack\Doctrine;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

final class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register config merge.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            path: __DIR__ . '/../config/doctrine.php',
            key: 'doctrine'
        );
    }

    /**
     * Publish config file.
     */
    public function boot(): void
    {
        $this->publishes(
            paths: [
                __DIR__ . '/../config/doctrine.php' => config_path(
                    path: 'doctrine.php'
                )
            ],
            groups: 'config'
        );
    }
}
