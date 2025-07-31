<?php declare(strict_types=1);

namespace App\Shared\Presentation;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController
{
    /**
     * Authorizes requests, dispatches jobs, and validates requests.
     */
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
