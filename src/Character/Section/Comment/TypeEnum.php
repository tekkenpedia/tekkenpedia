<?php

declare(strict_types=1);

namespace App\Character\Section\Comment;

use App\{
    Enum\CreateTrait,
    Enum\GetNamesTrait
};

enum TypeEnum
{
    use CreateTrait;
    use GetNamesTrait;

    case DEFENSE;
}
