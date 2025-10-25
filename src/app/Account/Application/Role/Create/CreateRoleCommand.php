<?php declare(strict_types=1);

namespace App\Account\Application\Role\Create;

use App\Shared\Application\Command\Command;
use WendellAdriel\ValidatedDTO\Casting\StringCast;
use WendellAdriel\ValidatedDTO\Attributes\Cast;

final class CreateRoleCommand extends Command
{
    /**
     * The name of the role.
     *
     * @var string
     */
    #[Cast(type: StringCast::class, param: null)]
    public string $name;

    /**
     * The slug identifier for the role.
     *
     * @var string
     */
    #[Cast(type: StringCast::class, param: null)]
    public string $slug;
}
