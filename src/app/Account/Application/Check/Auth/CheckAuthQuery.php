<?php declare(strict_types=1);

namespace App\Account\Application\Check\Auth;

use App\Shared\Application\Query\Query;
use Illuminate\Http\Request;

final class CheckAuthQuery extends Query
{
    /**
     * Constructs a new CheckAuthQuery instance.
     *
     * @param Request $request
     */
	public function __construct(
		public private(set) Request $request
	) {}
}
