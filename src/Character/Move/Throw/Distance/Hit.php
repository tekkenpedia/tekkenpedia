<?php

declare(strict_types=1);

namespace App\Character\Move\Throw\Distance;

readonly class Hit
{
    public function __construct(public int $normal, public ?int $ukemi = null)
    {
    }
}
