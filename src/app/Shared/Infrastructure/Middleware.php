<?php declare(strict_types=1);

namespace App\Shared\Infrastructure;

use Illuminate\Http\Request;

abstract class Middleware
{
	/**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * 
     * @return mixed
     */
	abstract public function handle(Request $request, \Closure $next): mixed;
}
