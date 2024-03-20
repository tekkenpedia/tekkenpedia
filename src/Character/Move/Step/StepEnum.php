<?php

declare(strict_types=1);

namespace App\Character\Move\Step;

use App\{
    Enum\CreateTrait,
    Enum\GetNamesTrait
};

enum StepEnum
{
    use GetNamesTrait;
    use CreateTrait;

    case EASY;
    case MEDIUM;
    case HARD;
    case IMPOSSIBLE;
}
