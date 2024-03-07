<?php

declare(strict_types=1);

namespace App\Move;

use App\{
    Enum\CreateTrait,
    Enum\GetEnumValuesTrait
};

enum PropertyEnum
{
    use GetEnumValuesTrait;
    use CreateTrait;

    case HIGH;
    case MIDDLE;
    case LOW;
}
