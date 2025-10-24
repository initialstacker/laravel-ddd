<?php declare(strict_types=1);

namespace App\Account\Application\Auth\Check\Me;

use App\Shared\Application\Query\Query;
use Illuminate\Http\Request;

final class CheckMeQuery extends Query
{
    /**
     * Constructs a new CheckMeQuery instance.
     *
     * @param Request $request
     */
	public function __construct(
		public private(set) Request $request
	) {}
}
