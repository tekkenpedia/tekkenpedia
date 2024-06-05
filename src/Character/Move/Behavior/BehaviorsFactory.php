<?php

declare(strict_types=1);

namespace App\Character\Move\Behavior;

use App\Collection\Character\Move\BehaviorEnumCollection;

class BehaviorsFactory
{
    /** @param array<string> $behaviors */
    public static function create(array $behaviors): BehaviorEnumCollection
    {
        $return = new BehaviorEnumCollection();
        foreach ($behaviors as $behavior) {
            $return->add(BehaviorEnum::create($behavior));
        }

        return $return;
    }
}
