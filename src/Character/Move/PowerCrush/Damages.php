<?php

declare(strict_types=1);

namespace App\Character\Move\PowerCrush;

use App\Character\Move\Damages as DamagesData;

readonly class Damages
{
    public function __construct(
        public DamagesData $block,
        public DamagesData $normalHit,
        public DamagesData $counterHit
    ) {
    }
}
