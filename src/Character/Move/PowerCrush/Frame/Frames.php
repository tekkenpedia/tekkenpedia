<?php

declare(strict_types=1);

namespace App\Character\Move\PowerCrush\Frame;

use App\{
    Character\Move\FramesBlockInterface,
    Character\Move\FramesInterface,
    Character\Move\MinMaxFramesInterface
};

readonly class Frames implements FramesInterface, FramesBlockInterface
{
    public function __construct(
        public Startup $startup,
        public Absorption $absorption,
        public AfterAbsorption $afterAbsorption,
        public Block $block,
        public int $normalHit,
        public int $counterHit
    ) {
    }

    public function getBlock(): MinMaxFramesInterface
    {
        return $this->block;
    }
}
