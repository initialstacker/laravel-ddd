<?php declare(strict_types=1);

namespace App\Account\Application\Login;

use App\Shared\Application\Command\Command;
use WendellAdriel\ValidatedDTO\Casting\StringCast;
use WendellAdriel\ValidatedDTO\Attributes\Cast;

final class LoginCommand extends Command
{
    /**
     * The email address of the user to register.
     *
     * @var string
     */
    #[Cast(type: StringCast::class, param: null)]
    public string $email;

    /**
     * The password for the new user account.
     *
     * @var string
     */
    #[Cast(type: StringCast::class, param: null)]
    public string $password;
}
