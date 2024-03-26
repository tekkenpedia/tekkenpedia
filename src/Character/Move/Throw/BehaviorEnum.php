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

    case WALL_SPLAT_BREAK_BOUND;
    case FLOOR_BREAK_BLAST;
    case KNOCKDOWN;
    case AIR;
    case DELETE_RECOVERABLE_LIFE_BAR;
    case HEAT_ENGAGER;
    case POWER_CRUSH;
}
