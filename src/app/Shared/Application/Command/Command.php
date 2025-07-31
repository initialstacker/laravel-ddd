<?php declare(strict_types=1);

namespace App\Shared\Application\Command;

use WendellAdriel\ValidatedDTO\SimpleDTO;
use WendellAdriel\ValidatedDTO\Concerns\EmptyCasts;

abstract class Command extends SimpleDTO
{
    use EmptyCasts;

    /**
     * Defines validation rules for the command.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Specifies default values for the command.
     *
     * @return array<string, mixed>
     */
    public function defaults(): array
    {
        return [];
    }

    /**
     * Defines data type casts for the command.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [];
    }
}
