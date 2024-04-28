<?php

declare(strict_types=1);

namespace App\Character\Move;

interface MinMaxFramesInterface
{
    public function getMin(): int;

    public function getMax(): ?int;
}
