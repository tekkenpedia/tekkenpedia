<?php

declare(strict_types=1);

namespace App\Character\Move\Attack;

use App\Collection\Character\Move\BehaviorEnumCollection;

readonly class Behaviors
{
    public function __construct(
        public BehaviorEnumCollection $block,
        public BehaviorEnumCollection $normalHit,
        public BehaviorEnumCollection $counterHit,
    ) {
    }
}
