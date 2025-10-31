<?php declare(strict_types=1);

namespace App\Account\Infrastructure;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Shared\Domain\Id\RoleId;
use App\Shared\Domain\Id\PermissionId;
use App\Shared\Domain\Id\UserId;

final class RouteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::bind(key: 'roleId',
            binder: function (string $id): RoleId {
                return RoleId::fromString(value: $id);
            }
        );

        Route::bind(key: 'permissionId',
            binder: function (string $id): PermissionId {
                return PermissionId::fromString(value: $id);
            }
        );

        Route::bind(key: 'userId',
            binder: function (string $id): UserId {
                return UserId::fromString(value: $id);
            }
        );
    }
}
