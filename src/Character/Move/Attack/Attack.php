<?php

declare(strict_types=1);

namespace App\Character\Move\Attack;

use App\{
    Character\Move\Attack\Frame\Frames,
    Character\Move\MoveInterface,
    Character\Move\Step\Steps,
    Character\Move\Visibility,
    Collection\Character\Move\BehaviorEnumCollection,
    Collection\Character\Move\CommentCollection,
    Collection\Character\Move\PatchNote\PatchNoteCollection
};

readonly class Attack implements MoveInterface
{
    public function __construct(
        public string $id,
        public string $name,
        public string $slug,
        public Visibility $visibility,
        public PropertyEnum $property,
        public Distances $distances,
        public Frames $frames,
        public Damages $damages,
        public BehaviorEnumCollection $normalHitBehaviors,
        public BehaviorEnumCollection $counterHitBehaviors,
        public Steps $steps,
        public CommentCollection $comments,
        /** @var array<PatchNoteCollection> $patchNotes */
        public array $patchNotes
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
