<?php

return [
    Initialstack\Doctrine\DoctrineConnector::class,
    Initialstack\Telescope\TelescopeServiceProvider::class,
    App\Shared\Infrastructure\BusServiceProvider::class,
    App\Account\Infrastructure\Auth\AuthServiceProvider::class,
    App\Account\Infrastructure\Dispatching\CommandDispatcher::class,
    App\Account\Infrastructure\Dispatching\QueryDispatcher::class,
    App\Account\Infrastructure\EventServiceProvider::class,
    App\Account\Infrastructure\RepositoryBinding::class,
];
