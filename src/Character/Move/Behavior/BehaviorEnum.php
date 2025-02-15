<?php

declare(strict_types=1);

namespace App\Character\Move\Behavior;

use App\{
    Collection\Character\Move\BehaviorEnumCollection,
    Enum\CreateTrait,
    Enum\GetNamesTrait
};

enum BehaviorEnum
{
    use CreateTrait;
    use GetNamesTrait;

    case WALL_SPLAT;
    case WALL_BREAK;
    case HARD_WALL_BREAK;
    case WALL_BOUND;
    case WALL_BLAST;
    case FLOOR_BREAK;
    case FLOOR_BLAST;
    case KNOCKDOWN;
    case AIR;
    case DELETE_RECOVERABLE_LIFE_BAR;
    case HEAT_BURST;
    case HEAT_ENGAGER;
    case OPPONENT_CROUCH;

    public static function getWallBehaviors(): BehaviorEnumCollection
    {
        return new BehaviorEnumCollection(
            [
                static::WALL_SPLAT,
                static::WALL_BREAK,
                static::HARD_WALL_BREAK,
                static::WALL_BOUND,
                static::WALL_BLAST,
            ]
        );
    }

    public static function getFloorBehaviors(): BehaviorEnumCollection
    {
        return new BehaviorEnumCollection(
            [
                static::FLOOR_BREAK,
                static::FLOOR_BLAST
            ]
        );
    }
}
