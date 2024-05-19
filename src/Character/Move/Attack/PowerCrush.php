<?php

declare(strict_types=1);

namespace App\Character\Move\Attack;

readonly class PowerCrush
{
    public function __construct(public ?int $damageReduction)
    {
    }
}
