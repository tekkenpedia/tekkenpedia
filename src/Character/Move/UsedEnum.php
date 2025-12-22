<?php

declare(strict_types=1);

namespace App\Character\Move;

use App\{
    Enum\CreateTrait,
    Enum\GetNamesTrait
};

enum UsedEnum
{
    use CreateTrait;
    use GetNamesTrait;

    case RARELY;
    case SITUATIONALLY;
    case FREQUENTLY;
}
