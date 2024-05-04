<?php

declare(strict_types=1);

namespace App\Character\Move;

readonly class Visibility
{
    public function __construct(public bool $defense)
    {
    }
}
