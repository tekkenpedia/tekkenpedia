<?php

declare(strict_types=1);

namespace App\Move;

use App\Collection\Move\MoveCollection;

readonly class Section
{
    public function __construct(public string $name, public MoveCollection $moves)
    {
    }
}
