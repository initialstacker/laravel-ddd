<?php declare(strict_types=1);

namespace App\Account\Application\Login;

use App\Shared\Application\Command\Command;
use WendellAdriel\ValidatedDTO\Casting\StringCast;
use WendellAdriel\ValidatedDTO\Casting\BooleanCast;
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

    /**
     * Whether to remember the user (i.e. keep them logged in).
     *
     * @var bool
     */
    #[Cast(type: BooleanCast::class, param: null)]
    public bool $rememberMe = false;
    
    /**
     * Maps properties to data keys.
     *
     * @return array<string, string>
     */
    protected function mapData(): array
    {
        return [
            'remember_me' => 'rememberMe'
        ];
    }
}
