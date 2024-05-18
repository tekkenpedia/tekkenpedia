<?php

declare(strict_types=1);

namespace App\Collection\Character\Move;

use App\Character\Move\Behavior\BehaviorEnum;
use Steevanb\PhpCollection\ObjectCollection\AbstractObjectCollection;

/** @extends AbstractObjectCollection<BehaviorEnum> */
class BehaviorEnumCollection extends AbstractObjectCollection
{
    public static function getValueFqcn(): string
    {
        return BehaviorEnum::class;
    }
}
