<?php declare(strict_types=1);

namespace App\Account\Application\User\List;

final class ShowUserQuery extends Query
{
    /**
     * Constructs a new ShowUserQuery instance.
     *
     * @param string $userId
     */
	public function __construct(
		public private(set) string $userId
	) {}
}
