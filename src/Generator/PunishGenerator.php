<?php

declare(strict_types=1);

namespace App\Generator;

use App\Character\Move\MoveInterface;

class PunishGenerator extends AbstractMovesGenerator
{
    protected function getType(): MovesGeneratorTypeEnum
    {
        return MovesGeneratorTypeEnum::PUNISH;
    }

    protected function isMoveVisible(MoveInterface $move): bool
    {
        return $move->getVisibility()->punish;
    }
}
