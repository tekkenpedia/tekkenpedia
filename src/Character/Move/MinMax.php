<?php

declare(strict_types=1);

namespace App\Character\Move;

readonly class MinMax
{
    public function __construct(public ?int $min, public ?int $max)
    {
    }
}
