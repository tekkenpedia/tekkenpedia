<?php

declare(strict_types=1);

namespace App\Character\Move\Throw\Distance;

readonly class Distances
{
    public function __construct(public int $startup, public Hit $hit, public ?int $escape = null)
    {
    }
}
