<?php

declare(strict_types=1);

namespace App\Character\Move;

use App\Character\Move\Step\Steps;
use App\Collection\Move\CommentCollection;

readonly class Move
{
    public function __construct(
        public string $name,
        public PropertyEnum $property,
        public int $distance,
        public Frames $frames,
        public Damages $damages,
        public Hits $hits,
        public Steps $steps,
        public CommentCollection $comments
    ) {
    }
}
