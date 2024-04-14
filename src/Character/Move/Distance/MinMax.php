<?php

declare(strict_types=1);

namespace App\Character\Move\Distance;

readonly class MinMax
{
    public function __construct(public ?int $min = null, public ?int $max = null)
    {
    }
}
