<?php declare(strict_types=1);

namespace App\Account\Application\Register;

use App\Shared\Application\Command\Command;
use WendellAdriel\ValidatedDTO\Casting\StringCast;
use WendellAdriel\ValidatedDTO\Attributes\Cast;

final class RegisterCommand extends Command
{
    /**
     * The name of the user to register.
     *
     * @var string
     */
    #[Cast(type: StringCast::class, param: null)]
    public string $name;

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
