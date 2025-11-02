<?php

declare(strict_types=1);

namespace App\Character\Move\Attack;

use App\{
    Enum\CreateInterface,
    Enum\CreateTrait,
    Enum\GetNamesInterface,
    Enum\GetNamesTrait
};

enum PropertyEnum implements CreateInterface, GetNamesInterface
{
    use CreateTrait;
    use GetNamesTrait;

    case HIGH;
    case MIDDLE;
    case SPECIAL_MIDDLE;
    case LOW;
    case SPECIAL_LOW;
}
