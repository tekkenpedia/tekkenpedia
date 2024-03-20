<?php

declare(strict_types=1);

namespace App\Character\Move\Comment;

use App\{
    Enum\CreateTrait,
    Enum\GetNamesTrait
};

enum TypeEnum
{
    use CreateTrait;
    use GetNamesTrait;

    case NORMAL;
    case DEFENSE;
    case STRENGTH;
}
