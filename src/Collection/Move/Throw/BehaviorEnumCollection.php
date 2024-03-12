<?php

declare(strict_types=1);

namespace App\Collection\Move\Throw;

use App\Move\Throw\BehaviorEnum;
use Steevanb\PhpCollection\EnumCollection\AbstractEnumCollection;

class BehaviorEnumCollection extends AbstractEnumCollection
{
    public function __construct()
    {
        parent::__construct(BehaviorEnum::class);
    }

    public function add(BehaviorEnum $move): static
    {
        return $this->doAdd($move);
    }

    /** @return array<BehaviorEnum> */
    public function toArray(): array
    {
        return parent::toArray();
    }
}
