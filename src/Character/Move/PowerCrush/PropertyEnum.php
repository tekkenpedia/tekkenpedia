<?php

declare(strict_types=1);

namespace App\Character\Move\PowerCrush;

use App\{
    Enum\CreateTrait,
    Enum\GetNamesTrait
};

enum PropertyEnum
{
    use GetNamesTrait;
    use CreateTrait;

    case HIGH;
    case MIDDLE;
}
