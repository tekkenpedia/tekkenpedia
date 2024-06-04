<?php

declare(strict_types=1);

namespace App\Character\Move\PowerCrush\Frame;

use App\Character\Move\MinMaxFramesInterface;

readonly class Block implements MinMaxFramesInterface
{
    public function __construct(public ?int $min, public ?int $max)
    {
    }

    public function getMin(): ?int
    {
        return $this->min;
    }

    public function getMax(): ?int
    {
        return $this->max;
    }
}
