<?php declare(strict_types=1);

namespace App\Account\Infrastructure\Dispatching;

use Illuminate\Support\ServiceProvider;
use App\Account\Application\Auth\Check\Me\CheckMeHandler;
use App\Account\Application\Auth\Check\Me\CheckMeQuery;
use App\Account\Application\Auth\Check\Auth\CheckAuthHandler;
use App\Account\Application\Auth\Check\Auth\CheckAuthQuery;
use App\Account\Application\Profile\Show\ShowProfileHandler;
use App\Account\Application\Profile\Show\ShowProfileQuery;
use App\Shared\Domain\Bus\QueryBusInterface;

final class QueryDispatcher extends ServiceProvider
{
    /**
     * Auth related query handlers
     * 
     * @var array<class-string, class-string>
     */
    private array $auth = [
        CheckMeQuery::class => CheckMeHandler::class,
        CheckAuthQuery::class => CheckAuthHandler::class,
    ];
    
    /**
     * Profile related query handlers
     * 
     * @var array<class-string, class-string>
     */
    private array $profile = [
        ShowProfileQuery::class => ShowProfileHandler::class,
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot(QueryBusInterface $queryBus): void
    {
        $queryBus->register(map: [
            ...$this->auth,
            ...$this->profile,
        ]);
    }
}
