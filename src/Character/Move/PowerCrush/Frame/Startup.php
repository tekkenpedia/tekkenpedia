<?php

declare(strict_types=1);

namespace App\Character\Move\PowerCrush\Frame;

use App\Character\Move\MinMaxFramesInterface;

readonly class Startup implements MinMaxFramesInterface
{
    public function __construct(public int $min, public ?int $max = null)
    {
    }

    public function getMin(): int
    {
        return $this->min;
    }

    public function getMax(): ?int
    {
        return $this->max;
    }
}
