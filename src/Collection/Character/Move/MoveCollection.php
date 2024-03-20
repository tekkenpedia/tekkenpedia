<?php

declare(strict_types=1);

namespace App\Collection\Character\Move;

use App\Character\Move\Move;
use Steevanb\PhpCollection\ObjectCollection\AbstractObjectCollection;

class MoveCollection extends AbstractObjectCollection
{
    public function __construct()
    {
        parent::__construct(Move::class);
    }

    public function add(Move $move): static
    {
        return $this->doAdd($move);
    }
}
