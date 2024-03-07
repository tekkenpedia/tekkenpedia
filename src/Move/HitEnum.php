<?php

declare(strict_types=1);

namespace App\Move;

use App\{
    Enum\CreateTrait,
    Enum\GetEnumValuesTrait
};

enum HitEnum
{
    use GetEnumValuesTrait;
    use CreateTrait;

    case HIT;
    case KNOCKDOWN;
}
