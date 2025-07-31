<?php declare(strict_types=1);

namespace Initialstack\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Context, Log};
use Illuminate\Support\Str;

final class ApiRequestLogger
{
    /**
     * Handles the request by logging relevant data and passing it to the next middleware.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * 
     * @return mixed
     */
    public function handle(Request $request, \Closure $next): mixed
    {
        Context::add(key: 'request_id', value: Str::uuid()->toString());
        Context::add(key: 'timestamp', value: now()->toIso8601String());
        Context::add(key: 'path', value: $request->path());
        Context::add(key: 'method', value: $request->method());

        $user = $request->user();

        if (is_object(value: $user) && property_exists(
            object_or_class: $user, property: 'id')) {
            Context::add(key: 'user_id', value: $user->id);
        }

        $startTime = microtime(as_float: true);
        $response = $next($request);

        $responseTime = round(
            num: (microtime(as_float: true) - $startTime) * 1000,
            precision: 2
        );

        Context::add(key: 'response_time', value: $responseTime);
        Context::add(key: 'status_code', value: $response->getStatusCode());

        Log::info(message: 'API Request Processed');

        return $response;
    }
}
