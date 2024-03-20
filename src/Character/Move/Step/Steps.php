<?php

declare(strict_types=1);

namespace App\Character\Move\Step;

readonly class Steps
{
    public function __construct(
        public StepEnum $ssl,
        public StepEnum $swl,
        public StepEnum $ssr,
        public StepEnum $swr
    ) {
    }
}
