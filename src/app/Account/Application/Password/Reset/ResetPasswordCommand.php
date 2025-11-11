<?php declare(strict_types=1);

namespace App\Account\Application\Password\Reset;

use App\Shared\Application\Command\Command;
use WendellAdriel\ValidatedDTO\Casting\StringCast;
use WendellAdriel\ValidatedDTO\Attributes\Cast;

final class ResetPasswordCommand extends Command
{
    /**
     * The email address of the user requesting password reset.
     *
     * @var string
     */
    #[Cast(type: StringCast::class, param: null)]
    public string $email;

    /**
     * The new password to set for the user.
     *
     * @var string
     */
    #[Cast(type: StringCast::class, param: null)]
    public string $password;

    /**
     * The password confirmation for validation.
     *
     * @var string
     */
    #[Cast(type: StringCast::class, param: null)]
    public string $passwordConfirmation;

    /**
     * The password reset token sent to the user's email.
     *
     * @var string
     */
    #[Cast(type: StringCast::class, param: null)]
    public string $token;
    
    /**
     * Maps properties to data keys.
     *
     * @return array<string, string>
     */
    protected function mapData(): array
    {
        return [
            'password_confirmation' => 'passwordConfirmation'
        ];
    }
}
