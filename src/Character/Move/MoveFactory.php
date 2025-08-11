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
        switch ($moveData['type']) {
            case MoveTypeEnum::ATTACK->name:
                /** @var TAttack $moveData */
                $return = AttackFactory::create($id, $moveData);
                break;
            case MoveTypeEnum::POWER_CRUSH->name:
                /** @var TPowerCrush $moveData */
                $return = PowerCrushFactory::create($id, $moveData);
                break;
            case MoveTypeEnum::THROW->name:
                /** @var TThrow $moveData */
                $return = ThrowFactory::create($id, $moveData);
                break;
            default:
                throw new AppException('Attack type "' . $moveData['type'] . '" is not taken into account.');
        }

        return $return;
    }
}
