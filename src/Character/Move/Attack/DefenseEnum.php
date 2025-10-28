<?php

declare(strict_types=1);

namespace App\Character\Move\Attack;

use App\{
    Enum\CreateTrait,
    Enum\GetNamesTrait
};

enum DefenseEnum
{
    use CreateTrait;
    use GetNamesTrait;

    case PUNISH;
    case SSL;
    case SWL;
    case SSR;
    case SWR;
    case DUCK;
    case REVERSAL;
    case POWER_CRUSH;
    case LOW_PARRY;
    case THROW;
}
