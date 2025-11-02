<?php

declare(strict_types=1);

namespace App\Collection\Character\Move\PowerCrush;

use App\Character\Move\PowerCrush\PropertyEnum;
use Steevanb\PhpCollection\ObjectCollection\AbstractObjectCollection;

/** @extends AbstractObjectCollection<PropertyEnum> */
class PropertyEnumCollection extends AbstractObjectCollection
{
    public static function getValueFqcn(): string
    {
        return PropertyEnum::class;
    }
}
