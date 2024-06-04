<?php

declare(strict_types=1);

namespace App\Character\Move;

use App\Parser\Character\Move\MoveTypeEnum;

interface MoveInterface
{
    public function getSlug(): string;

    public function getVisibility(): Visibility;

    public function getType(): MoveTypeEnum;
}
