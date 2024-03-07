<?php

declare(strict_types=1);

namespace App\Move;

readonly class Frames
{
    public function __construct(
        public int $startup,
        public int $normalHit,
        public int $counterHit,
        public int $block
    ) {
    }
}
