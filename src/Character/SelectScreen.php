<?php

declare(strict_types=1);

namespace App\Character;

readonly class SelectScreen
{
    public function __construct(public int $line, public int $position)
    {
    }
}
