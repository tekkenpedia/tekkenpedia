<?php

declare(strict_types=1);

namespace App\Character\Move;

use App\{
    Collection\Character\Move\Attack\DefenseEnumCollection,
    Parser\Character\Move\MoveTypeEnum
};

interface MoveInterface
{
    public function getId(): string;

    public function getSlug(): string;

    public function getVisibility(): Visibility;

    public function getType(): MoveTypeEnum;

    public function getDefenses(): DefenseEnumCollection;

    public function getFrames(): FramesInterface;
}
