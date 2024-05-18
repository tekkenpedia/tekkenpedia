<?php

declare(strict_types=1);

namespace App\Character\Move\Throw\Frame;

readonly class Escape
{
    public function __construct(public ?int $normalHit = null, public ?int $counterHit = null)
    {
    }
}
