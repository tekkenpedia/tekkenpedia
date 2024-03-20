<?php

declare(strict_types=1);

namespace App\Collection\Move\Throw;

use App\Character\Move\Throw\Throw_;
use Steevanb\PhpCollection\ObjectCollection\AbstractObjectCollection;

class ThrowCollection extends AbstractObjectCollection
{
    public function __construct()
    {
        parent::__construct(Throw_::class);
    }

    public function add(Throw_ $move): static
    {
        return $this->doAdd($move);
    }
}
