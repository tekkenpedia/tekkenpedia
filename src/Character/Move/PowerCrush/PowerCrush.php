<?php

declare(strict_types=1);

namespace App\Character\Move\PowerCrush;

use App\{
    Character\Move\PowerCrush\Frame\Frames,
    Character\Move\MoveInterface,
    Character\Move\Step\Steps,
    Character\Move\Visibility,
    Collection\Character\Move\PowerCrush\PowerCrushCollection,
    Collection\Character\Move\CommentCollection,
    Exception\AppException,
    Parser\Character\Move\MoveTypeEnum};

class PowerCrush implements MoveInterface
{
    public PowerCrushCollection $slaves;

    private bool $masterIsDefined = false;

    private ?PowerCrush $master = null;

    public function __construct(
        public readonly ?string $masterId,
        public readonly string $id,
        public readonly string $inputs,
        public readonly ?string $situation,
        public readonly string $slug,
        public readonly bool $heat,
        public readonly Visibility $visibility,
        public readonly PropertyEnum $property,
        public readonly int $damageReduction,
        public readonly Distances $distances,
        public readonly Frames $frames,
        public readonly Damages $damages,
        public readonly Behaviors $behaviors,
        public readonly Steps $steps,
        public readonly CommentCollection $comments
    ) {
        $this->slaves = new PowerCrushCollection();
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getVisibility(): Visibility
    {
        return $this->visibility;
    }

    public function setMaster(?PowerCrush $master): static
    {
        if ($this->masterIsDefined) {
            throw new AppException('Master has already been defined.');
        }

        $this->master = $master;
        $this->masterIsDefined = true;

        return $this;
    }

    public function getMaster(): ?PowerCrush
    {
        return $this->master;
    }

    public function getType(): MoveTypeEnum
    {
        return MoveTypeEnum::POWER_CRUSH;
    }
}
