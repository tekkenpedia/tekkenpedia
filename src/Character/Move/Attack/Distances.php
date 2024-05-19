<?php

declare(strict_types=1);

namespace App\Character\Move\Attack;

use App\Character\Move\Distance\MinMax;

readonly class Distances
{
    public function __construct(
        public ?int $startup,
        public MinMax $block,
        public MinMax $normalHit,
        public MinMax $counterHit
    ) {
    }
}
