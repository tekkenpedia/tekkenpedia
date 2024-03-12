<?php

declare(strict_types=1);

namespace App\Move\Throw;

use App\{
    Enum\CreateTrait,
    Enum\GetNamesTrait
};

enum PropertyEnum
{
    use CreateTrait;
    use GetNamesTrait;

    case WALL_SPLAT;
    case WALL_BOUNCE;
    case FLOOR_BREAK;
    case FLOOR_BOUNCE;
}
