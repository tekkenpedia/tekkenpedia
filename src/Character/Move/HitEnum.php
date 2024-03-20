<?php

declare(strict_types=1);

namespace App\Character\Move;

use App\{
    Enum\CreateTrait,
    Enum\GetNamesTrait
};

enum HitEnum
{
    use GetNamesTrait;
    use CreateTrait;

    case HIT;
    case KNOCKDOWN;
    case AIR;
}
