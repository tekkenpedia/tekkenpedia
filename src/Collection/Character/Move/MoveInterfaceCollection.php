<?php

declare(strict_types=1);

namespace App\Collection\Character\Move;

use App\Character\Move\MoveInterface;
use Steevanb\PhpCollection\ObjectCollection\AbstractObjectCollection;

/** @extends AbstractObjectCollection<MoveInterface> */
class MoveInterfaceCollection extends AbstractObjectCollection
{
    public static function getValueFqcn(): string
    {
        return MoveInterface::class;
    }

    public function hasDefenseMoves(): bool
    {
        $return = false;
        foreach ($this->toArray() as $move) {
            if ($move->getVisibility()->defense) {
                $return = true;
                break;
            }
        }

        return $return;
    }
}
