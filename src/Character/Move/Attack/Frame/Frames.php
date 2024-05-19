<?php

declare(strict_types=1);

namespace App\Character\Move\Attack\Frame;

readonly class Frames
{
    public function __construct(
        public Startup $startup,
        public Absorption $absorption,
        public AfterAbsorption $afterAbsorption,
        public int $block,
        public int $normalHit,
        public int $counterHit
    ) {
    }
}
