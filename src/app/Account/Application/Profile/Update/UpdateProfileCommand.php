<?php declare(strict_types=1);

namespace App\Account\Application\Profile\Update;

use App\Shared\Application\Command\Command;
use App\Shared\Application\MediaCast;
use WendellAdriel\ValidatedDTO\Casting\ObjectCast;
use WendellAdriel\ValidatedDTO\Casting\StringCast;
use WendellAdriel\ValidatedDTO\Attributes\Cast;
use Illuminate\Http\UploadedFile;

final class UpdateProfileCommand extends Command
{
    /**
     * The user's avatar upload or null if absent.
     */
    #[Cast(type: MediaCast::class, param: null)]
    public ?UploadedFile $avatar = null;

    /**
     * The user's full name.
     */
    #[Cast(type: StringCast::class, param: null)]
    public string $name;

    /**
     * The user's email address.
     */
    #[Cast(type: StringCast::class, param: null)]
    public string $email;

    /**
     * The user's password.
     */
    #[Cast(type: StringCast::class, param: null)]
    public string $password;
}
