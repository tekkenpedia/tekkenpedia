<?php

declare(strict_types=1);

namespace App\Generator;

enum MovesGeneratorTypeEnum: string
{
    case DEFENSE = 'defense';
    case PUNISH = 'punish';
}
