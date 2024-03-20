<?php

declare(strict_types=1);

namespace App\Character\Move\Throw;

readonly class Distances
{
    public function __construct(public int $startup, public int $hit, public ?int $escape = null)
    {
    }
}
