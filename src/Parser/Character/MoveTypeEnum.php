<?php

declare(strict_types=1);

namespace App\Parser\Character;

use App\Enum\CreateTrait;

enum MoveTypeEnum
{
    use CreateTrait;

    case ATTACK;
    case THROW;
}
