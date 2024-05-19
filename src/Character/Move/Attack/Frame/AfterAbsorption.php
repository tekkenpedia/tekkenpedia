<?php

declare(strict_types=1);

namespace App\Character\Move\Attack\Frame;

readonly class AfterAbsorption
{
    public function __construct(public ?int $block)
    {
    }
}
