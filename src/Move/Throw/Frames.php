<?php

declare(strict_types=1);

namespace App\Move\Throw;

readonly class Frames
{
    public function __construct(
        public int $startup,
        public ?int $escape = null,
        public ?int $afterEscape = null
    ) {
    }
}
