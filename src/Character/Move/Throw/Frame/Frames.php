<?php

declare(strict_types=1);

namespace App\Character\Move\Throw\Frame;

readonly class Frames
{
    public function __construct(
        public Startup $startup,
        public Hit $hit,
        public ?int $escape = null,
        public ?int $afterEscape = null
    ) {
    }
}
