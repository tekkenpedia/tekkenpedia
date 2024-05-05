<?php

declare(strict_types=1);

namespace App\Character\Move\PatchNote;

use App\{
    Enum\CreateTrait,
    Enum\GetNamesTrait
};

enum PatchNoteTypeEnum
{
    use CreateTrait;
    use GetNamesTrait;

    case UP;
    case NERF;
    case CHANGE;
}
