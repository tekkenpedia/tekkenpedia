<?php

declare(strict_types=1);

namespace App\Character\Move\Throw\Frame\Hit;

readonly class Wall
{
    public function __construct(public ?int $normal = null, public ?int $splat = null, public ?int $break = null)
    {
    }
}
