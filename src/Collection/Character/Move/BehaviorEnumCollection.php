<?php

declare(strict_types=1);

namespace App\Collection\Character\Move;

use App\Character\Move\Behavior\BehaviorEnum;
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

    public function contains(BehaviorEnum $behavior): bool
    {
        return in_array($behavior, $this->toArray());
    }

    /** @return array<BehaviorEnum> */
    public function toArray(): array
    {
        return parent::toArray();
    }
}