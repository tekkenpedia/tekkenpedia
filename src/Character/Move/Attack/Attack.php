<?php

declare(strict_types=1);

namespace App\Character\Move\Attack;

use App\{
    Character\Move\Attack\Frame\Frames,
    Character\Move\MoveInterface,
    Character\Move\Step\Steps,
    Character\Move\Visibility,
    Collection\Character\Move\BehaviorEnumCollection,
    Collection\Character\Move\CommentCollection
};

readonly class Attack implements MoveInterface
{
    public function __construct(
        public string $id,
        public string $name,
        public string $slug,
        public bool $heat,
        public Visibility $visibility,
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

    public function getVisibility(): Visibility
    {
        return $this->visibility;
    }
}
