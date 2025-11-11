<?php declare(strict_types=1);

namespace App\Account\Application\Password\Forgot;

use App\Shared\Application\Command\Command;
use WendellAdriel\ValidatedDTO\Casting\StringCast;
use WendellAdriel\ValidatedDTO\Attributes\Cast;

final class ForgotPasswordCommand extends Command
{
    /**
     * The email address used to request a password reset.
     *
     * @var string
     */
    #[Cast(type: StringCast::class, param: null)]
    public string $email;
}
