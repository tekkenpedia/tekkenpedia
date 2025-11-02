<?php

declare(strict_types=1);

namespace App\Character\Move\Throw;

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
    case LOW;
}
