<?php

declare(strict_types=1);

namespace App\Collection\Move\Throw;

use App\Move\Throw\PropertyEnum;
use Steevanb\PhpCollection\EnumCollection\AbstractEnumCollection;

class PropertyEnumCollection extends AbstractEnumCollection
{
    public function __construct()
    {
        parent::__construct(PropertyEnum::class);
    }

    public function add(PropertyEnum $move): static
    {
        return $this->doAdd($move);
    }

    /** @return array<PropertyEnum> */
    public function toArray(): array
    {
        return parent::toArray();
    }
}
