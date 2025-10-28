<?php

declare(strict_types=1);

namespace App\Character\Move\Throw\Frame;

use App\{
    Character\Move\FramesInterface,
    Character\Move\Throw\Frame\Hit\Hit
};

readonly class Frames implements FramesInterface
{
    public function __construct(
        public Startup $startup,
        public Hit $hit,
        public Escape $escape,
        public ?int $afterEscape = null
    ) {
    }
}
