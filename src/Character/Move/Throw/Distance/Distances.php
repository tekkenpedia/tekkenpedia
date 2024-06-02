<?php

declare(strict_types=1);

namespace App\Character\Move\Throw\Distance;

readonly class Distances
{
    public function __construct(public int $range, public Hit $hit, public ?int $escape = null)
    {
    }
}
