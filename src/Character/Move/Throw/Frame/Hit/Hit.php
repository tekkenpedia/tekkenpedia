<?php

declare(strict_types=1);

namespace App\Character\Move\Throw\Frame\Hit;

readonly class Hit
{
    public function __construct(public int $normal, public Wall $wall, public ?int $ukemi = null)
    {
    }
}
