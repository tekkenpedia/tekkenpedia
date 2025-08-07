<?php

declare(strict_types=1);

namespace App\Generator;

use App\Character\Move\MoveInterface;

class DefenseGenerator extends AbstractMovesGenerator
{
    protected function getType(): MovesGeneratorTypeEnum
    {
        return MovesGeneratorTypeEnum::DEFENSE;
    }

    protected function isMoveVisible(MoveInterface $move): bool
    {
        return $move->getVisibility()->defense;
    }
}
