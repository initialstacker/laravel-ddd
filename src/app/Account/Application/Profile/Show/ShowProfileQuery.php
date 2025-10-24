<?php declare(strict_types=1);

namespace App\Account\Application\Profile\Show;

use App\Shared\Application\Query\Query;
use App\Shared\Domain\Id\UserId;
use Illuminate\Support\Facades\Auth;

final class ShowProfileQuery extends Query
{
    /**
     * The unique identifier of the authenticated user.
     *
     * @var UserId
     */
    public private(set) UserId $userId;

    /**
     * Constructs a new ShowProfileQuery instance.
     * 
     * @throws \RuntimeException
     */
    public function __construct()
    {
        /** @var AuthUserAdapter|null $auth */
        $auth = Auth::user();

        if ($auth === null) {
            throw new \RuntimeException(
                message: 'No authenticated user found.'
            );
        }
        
        $this->userId = $auth->user->id;
    }
}
