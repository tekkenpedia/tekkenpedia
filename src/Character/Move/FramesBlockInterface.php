<?php

declare(strict_types=1);

namespace App\Character\Move;

interface FramesBlockInterface
{
    public function getBlock(): MinMaxFramesInterface;
}
