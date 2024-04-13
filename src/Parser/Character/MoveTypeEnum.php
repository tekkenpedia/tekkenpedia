<?php

declare(strict_types=1);

namespace App\Parser\Character;

use App\Exception\AppException;

enum MoveTypeEnum
{
    case ATTACK;
    case THROW;

    public static function create(string $name): static
    {
        switch ($name) {
            case 'ATTACK':
                $return = static::ATTACK;
                break;
            case 'THROW':
                $return = static::THROW;
                break;
            default:
                throw new AppException('Unknown move type "' . $name . '".');
        }

        return $return;
    }
}
