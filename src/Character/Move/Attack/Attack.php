<?php

declare(strict_types=1);

namespace App\Character\Move\Attack;

use App\{
    Character\Move\Attack\Frame\Frames,
    Character\Move\MoveInterface,
    Character\Move\Step\Steps,
    Character\Move\Visibility,
    Collection\Character\Move\Attack\AttackCollection,
    Collection\Character\Move\BehaviorEnumCollection,
    Collection\Character\Move\CommentCollection,
    Exception\AppException
};

class Attack implements MoveInterface
{
    public AttackCollection $slaves;

    private bool $masterIsDefined = false;

    private ?Attack $master = null;

    public function __construct(
        public readonly ?string $masterId,
        public readonly string $id,
        public readonly string $name,
        public readonly string $slug,
        public readonly bool $heat,
        public readonly Visibility $visibility,
        public readonly PropertyEnum $property,
        public readonly Distances $distances,
        public readonly Frames $frames,
        public readonly Damages $damages,
        public readonly BehaviorEnumCollection $normalHitBehaviors,
        public readonly BehaviorEnumCollection $counterHitBehaviors,
        public readonly Steps $steps,
        public readonly CommentCollection $comments
    ) {
        $this->slaves = new AttackCollection();
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getVisibility(): Visibility
    {
        return $this->visibility;
    }

    public function setMaster(?Attack $master): static
    {
        if ($this->masterIsDefined) {
            throw new AppException('Master has already been defined.');
        }

        $this->master = $master;
        $this->masterIsDefined = true;

        return $this;
    }

    public function getMaster(): ?Attack
    {
        return $this->master;
    }
}
