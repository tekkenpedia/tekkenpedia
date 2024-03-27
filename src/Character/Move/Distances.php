<?php

declare(strict_types=1);

namespace App\Character\Move;

readonly class Distances
{
    public function __construct(public MinMax $block, public MinMax $normalHit, public MinMax $counterHit)
    {
    }
}
