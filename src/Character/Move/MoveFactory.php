<?php

declare(strict_types=1);

namespace App\Character\Move;

use App\{
    Character\Move\Attack\Attack,
    Character\Move\Attack\AttackFactory,
    Character\Move\PowerCrush\PowerCrush,
    Character\Move\PowerCrush\PowerCrushFactory,
    Character\Move\Throw\Throw_,
    Character\Move\Throw\ThrowFactory,
    Exception\AppException,
    Parser\Character\Move\MoveTypeEnum
};

class MoveFactory
{
    /** @param TAttack|TPowerCrush|TThrow $moveData */
    public static function create(string $id, array &$moveData): Attack|PowerCrush|Throw_
    {
        return match ($moveData['type']) {
            MoveTypeEnum::ATTACK->name => AttackFactory::create($id, $moveData),
            MoveTypeEnum::POWER_CRUSH->name => PowerCrushFactory::create($id, $moveData),
            MoveTypeEnum::THROW->name => ThrowFactory::create($id, $moveData),
            default => throw new AppException('Attack type "' . $moveData['type'] . '" is not taken into account.')
        };
    }
}
