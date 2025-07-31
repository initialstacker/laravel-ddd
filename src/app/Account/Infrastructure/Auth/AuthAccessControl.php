<?php declare(strict_types=1);

namespace App\Account\Infrastructure\Auth;

use App\Account\Domain\User;
use App\Shared\Infrastructure\Middleware;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\{Request, Response};

final class AuthAccessControl extends Middleware
{
    /**
     * Handle the incoming request, checking user role for access.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * 
     * @return mixed
     */
    public function handle(Request $request, \Closure $next): mixed
    {
        if (!Auth::check()) {
            return $next($request);
        }

        /** @var \App\Account\Infrastructure\Auth\AuthUserAdapter|null $auth */
        $auth = Auth::user();

        if (!$auth instanceof Authenticatable) {
            abort(
                code: Response::HTTP_FORBIDDEN,
                message: 'Access denied. Invalid user.'
            );
        }
        
        $role = $auth->user->role;

        if ($role === null) {
            abort(
                code: Response::HTTP_FORBIDDEN,
                message: 'Access denied. Role not found.'
            );
        }

        $access = match ($role->slug->asString()) {
            'admin' => true,
            'user'  => false,
            default => false,
        };

        if (!$access) {
            abort(
                code: Response::HTTP_FORBIDDEN,
                message: 'Access denied.'
            );
        }

        return $next($request);
    }
}
