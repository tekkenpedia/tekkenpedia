<?php

declare(strict_types=1);

namespace App\Collection\Character\Move\Attack;

use App\Character\Move\Attack\DefenseEnum;
use Steevanb\PhpCollection\ObjectCollection\AbstractObjectCollection;

/** @extends AbstractObjectCollection<DefenseEnum> */
class DefenseEnumCollection extends AbstractObjectCollection
{
    public static function getValueFqcn(): string
    {
        return DefenseEnum::class;
    }

    /** @return string[] */
    public function toNamesArray(): array
    {
        $return = [];
        foreach ($this->values as $enum) {
            $return[] = $enum->name;
        }

        return $return;
    }
}
