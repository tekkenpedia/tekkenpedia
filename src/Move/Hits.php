<?php

declare(strict_types=1);

namespace App\Move;

readonly class Hits
{
    public function __construct(public HitEnum $normal, public HitEnum $counter)
    {
    }
}
