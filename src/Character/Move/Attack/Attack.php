<?php

declare(strict_types=1);

namespace App\Character\Move\Attack;

use App\{
    Character\Move\Attack\Frame\Frames,
    Character\Move\FramesInterface,
    Character\Move\MoveInterface,
    Character\Move\Step\Steps,
    Character\Move\UsedEnum,
    Character\Move\Visibility,
    Collection\Character\Move\Attack\DefenseEnumCollection,
    Collection\Character\Move\Attack\PropertyEnumCollection,
    Collection\Character\Move\CommentCollection,
    Parser\Character\Move\MoveTypeEnum
};

readonly class Attack implements MoveInterface
{
    public function __construct(
        public string $id,
        public string $inputs,
        public ?UsedEnum $used,
        public DefenseEnumCollection $defenses,
        public string $slug,
        public bool $heat,
        public Visibility $visibility,
        public PropertyEnumCollection $properties,
        public Distances $distances,
        public Frames $frames,
        public Damages $damages,
        public Behaviors $behaviors,
        public Steps $steps,
        public CommentCollection $comments
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getVisibility(): Visibility
    {
        return $this->visibility;
    }

    public function getType(): MoveTypeEnum
    {
        return MoveTypeEnum::ATTACK;
    }

    public function getDefenses(): DefenseEnumCollection
    {
        return $this->defenses;
    }

    public function getFrames(): FramesInterface
    {
        return $this->frames;
    }
}
