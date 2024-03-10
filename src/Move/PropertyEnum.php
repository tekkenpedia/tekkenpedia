<?php

declare(strict_types=1);

namespace App\Move;

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
    case LOW;
}
