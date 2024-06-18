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
    /** @param TAttack|TPowerCrush|TThrow $move */
    public static function create(string $id, array &$moveData): Attack|PowerCrush|Throw_
    {
        switch ($moveData['type']) {
            case MoveTypeEnum::ATTACK->name:
                $return = AttackFactory::create($id, $moveData);
                break;
            case MoveTypeEnum::POWER_CRUSH->name:
                $return = PowerCrushFactory::create($id, $moveData);
                break;
            case MoveTypeEnum::THROW->name:
                $return = ThrowFactory::create($id, $moveData);
                break;
            default:
                throw new AppException('Attack type "' . $moveData['type'] . '" is not taken into account.');
        }

        return $return;
    }
}
