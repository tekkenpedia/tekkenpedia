<?php

declare(strict_types=1);

namespace App\Move\Throw;

readonly class Distances
{
    public function __construct(public int $startup, public ?int $afterEscape = null)
    {
    }
}
