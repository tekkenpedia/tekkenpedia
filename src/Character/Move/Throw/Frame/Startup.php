<?php

declare(strict_types=1);

namespace App\Character\Move\Throw\Frame;

readonly class Startup
{
    public function __construct(public int $min, public ?int $max = null)
    {
    }
}
