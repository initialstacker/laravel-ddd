<?php declare(strict_types=1);

namespace App\Shared\Application;

use WendellAdriel\ValidatedDTO\Casting\Castable;
use Illuminate\Http\UploadedFile;

final class MediaCast implements Castable
{
    /**
     * Casts the given value to an UploadedFile or null.
     *
     * @param string $property
     * @param mixed $value
     * 
     * @return UploadedFile|null
     */
    public function cast(
        string $property, mixed $value): ?UploadedFile
    {
        if ($value instanceof UploadedFile) {
            return $value;
        }

        return null;
    }
}
