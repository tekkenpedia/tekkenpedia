<?php

declare(strict_types=1);

namespace App\Move;

use App\{
    Enum\CreateTrait,
    Enum\GetEnumValuesTrait
};

enum StepEnum
{
    use GetEnumValuesTrait;
    use CreateTrait;

    case EASY;
    case MEDIUM;
    case HARD;
    case IMPOSSIBLE;
}
