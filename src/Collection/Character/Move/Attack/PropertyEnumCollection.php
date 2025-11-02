<?php

declare(strict_types=1);

namespace App\Collection\Character\Move\Attack;

use App\Character\Move\Attack\PropertyEnum;
use Steevanb\PhpCollection\{
    ObjectCollection\AbstractObjectCollection,
    ScalarCollection\StringCollection
};

/** @extends AbstractObjectCollection<PropertyEnum> */
class PropertyEnumCollection extends AbstractObjectCollection
{
    public static function getValueFqcn(): string
    {
        return PropertyEnum::class;
    }

    public function getStringValues(): StringCollection
    {
        $return = new StringCollection();
        foreach ($this->toArray() as $property) {
            $return->add($property->name);
        }

        return $return;
    }
}
