<?php

declare(strict_types=1);

namespace App\Collection\Character\Move\Attack;

use App\Character\Move\Attack\Attack;
use Steevanb\PhpCollection\ObjectCollection\AbstractObjectCollection;

/** @extends AbstractObjectCollection<Attack> */
class AttackCollection extends AbstractObjectCollection
{
    public static function getValueFqcn(): string
    {
        return Attack::class;
    }
}
