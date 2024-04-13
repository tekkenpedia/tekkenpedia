<?php

declare(strict_types=1);

namespace App\Character\Move\Attack;

use App\{
    Character\Move\MoveInterface,
    Character\Move\Step\Steps,
    Collection\Character\Move\BehaviorEnumCollection,
    Collection\Character\Move\CommentCollection
};

readonly class Attack implements MoveInterface
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

    public function getSlug(): string
    {
        return $this->slug;
    }
}
