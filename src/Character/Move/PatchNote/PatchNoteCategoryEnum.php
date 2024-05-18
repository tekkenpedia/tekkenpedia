<?php

declare(strict_types=1);

namespace App\Character\Move\PatchNote;

use App\{
    Enum\CreateTrait,
    Enum\GetNamesTrait
};

enum PatchNoteCategoryEnum
{
    use CreateTrait;
    use GetNamesTrait;

    case BEHAVIOR;
}
