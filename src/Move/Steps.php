<?php

declare(strict_types=1);

namespace App\Move;

readonly class Steps
{
    public function __construct(public StepEnum $ssl, public StepEnum $swl, public StepEnum $ssr, public StepEnum $swr)
    {
    }
}
