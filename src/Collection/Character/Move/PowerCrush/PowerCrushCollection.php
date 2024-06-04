<?php

declare(strict_types=1);

namespace App\Collection\Character\Move\PowerCrush;

use App\Character\Move\PowerCrush\PowerCrush;
use Steevanb\PhpCollection\ObjectCollection\AbstractObjectCollection;

/** @extends AbstractObjectCollection<PowerCrush> */
class PowerCrushCollection extends AbstractObjectCollection
{
    public static function getValueFqcn(): string
    {
        return PowerCrush::class;
    }
}
