<?php

declare(strict_types=1);

namespace App\Collection\Character\Move\Throw;

use App\Character\Move\Throw\PropertyEnum;
use Steevanb\PhpCollection\ObjectCollection\AbstractObjectCollection;

/** @extends AbstractObjectCollection<PropertyEnum> */
class PropertyEnumCollection extends AbstractObjectCollection
{
    public static function getValueFqcn(): string
    {
        return PropertyEnum::class;
    }
}
