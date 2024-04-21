<?php

declare(strict_types=1);

namespace App\Character\Move\Throw\Frame;

readonly class Hit
{
    public function __construct(public int $normal, public ?int $ukemi = null, public ?int $wall = null)
    {
    }
}
