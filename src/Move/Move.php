<?php

declare(strict_types=1);

namespace App\Move;

use Steevanb\PhpCollection\ScalarCollection\StringCollection;

readonly class Move
{
    public function __construct(
        public PropertyEnum $property,
        public int $distance,
        public Frames $frames,
        public Damages $damages,
        public Hits $hits,
        public Steps $steps,
        public StringCollection $comments
    ) {
    }
}