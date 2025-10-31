<?php

declare(strict_types=1);

namespace App\Character\Move\PowerCrush;

use App\{
    Character\Move\FramesInterface,
    Character\Move\MoveInterface,
    Character\Move\PowerCrush\Frame\Frames,
    Character\Move\Step\Steps,
    Character\Move\Visibility,
    Collection\Character\Move\Attack\DefenseEnumCollection,
    Collection\Character\Move\CommentCollection,
    Parser\Character\Move\MoveTypeEnum
};

readonly class PowerCrush implements MoveInterface
{
    public function __construct(
        public string $id,
        public string $inputs,
        public DefenseEnumCollection $defenses,
        public ?string $situation,
        public string $slug,
        public bool $heat,
        public Visibility $visibility,
        public ?PropertyEnum $property,
        public ?int $damageReduction,
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
        return MoveTypeEnum::POWER_CRUSH;
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
