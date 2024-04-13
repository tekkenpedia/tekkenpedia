<?php

declare(strict_types=1);

namespace App\Collection\Character\Move;

use App\Character\Move\MoveInterface;
use Steevanb\PhpCollection\ObjectCollection\AbstractObjectCollection;

class MoveInterfaceCollection extends AbstractObjectCollection
{
    public function __construct()
    {
        parent::__construct(MoveInterface::class);
    }

    public function add(MoveInterface $move): static
    {
        return $this->doAdd($move);
    }

    /** @return array<MoveInterface> */
    public function toArray(): array
    {
        return parent::toArray();
    }
}
