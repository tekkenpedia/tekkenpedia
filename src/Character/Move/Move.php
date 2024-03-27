<?php

declare(strict_types=1);

namespace App\Character\Move;

use App\{
    Character\Move\Step\Steps,
    Collection\Character\Move\CommentCollection,
    Collection\Character\Move\Throw\BehaviorEnumCollection
};

readonly class Move
{
    public function __construct(
        public string $name,
        public string $slug,
        public PropertyEnum $property,
        public Distances $distances,
        public Frames $frames,
        public Damages $damages,
        public BehaviorEnumCollection $normalHitBehaviors,
        public BehaviorEnumCollection $counterHitBehaviors,
        public Steps $steps,
        public CommentCollection $comments
    ) {
    }
}
