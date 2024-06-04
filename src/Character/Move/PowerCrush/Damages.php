<?php

declare(strict_types=1);

namespace App\Character\Move\PowerCrush;

readonly class Damages
{
    public function __construct(public int $normalHit, public int $counterHit)
    {
    }
}
