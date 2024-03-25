<?php

declare(strict_types=1);

namespace App\Character\Move\Throw;

use App\{
    Enum\CreateTrait,
    Enum\GetNamesTrait
};

enum BehaviorEnum
{
    use CreateTrait;
    use GetNamesTrait;

    case WALL_SPLAT;
    case WALL_BREAK;
    case WALL_BOUND;
    case FLOOR_BREAK;
    case FLOOR_BLAST;
    case AIR;
    case DELETE_RECOVERABLE_LIFE_BAR;
    case HEAT_ENGAGER;
    case POWER_CRUSH;
}
