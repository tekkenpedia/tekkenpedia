<?php

declare(strict_types=1);

namespace App\Character\Move\Throw;

readonly class Damages
{
    public function __construct(public int $normal, public ?int $ukemi, public ?int $wall = null)
    {
    }
}
