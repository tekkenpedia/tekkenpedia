<?php

declare(strict_types=1);

namespace App\Character\Move;

readonly class Damages
{
    public function __construct(public ?int $damage, public ?int $recoverableDamage)
    {
    }
}
