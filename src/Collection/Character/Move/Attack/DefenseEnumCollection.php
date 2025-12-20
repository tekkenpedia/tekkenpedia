<?php

declare(strict_types=1);

namespace App\Collection\Character\Move\Attack;

use App\Character\Move\Attack\DefenseEnum;
use Steevanb\PhpCollection\ObjectCollection\AbstractObjectCollection;

/** @extends AbstractObjectCollection<DefenseEnum> */
class DefenseEnumCollection extends AbstractObjectCollection
{
    public static function getValueFqcn(): string
    {
        return DefenseEnum::class;
    }

    /** @return string[] */
    public function toNamesArray(): array
    {
        $return = [];
        foreach ($this->values as $enum) {
            $return[] = $enum->name;
        }

        return $return;
    }

    public function set(int|string $key, mixed $value): static
    {
        parent::set($key, $value);
        $this->sort();

        return $this;
    }

    public function add(mixed $value): static
    {
        parent::add($value);
        $this->sort();

        return $this;
    }

    public function sort(): static
    {
        $sorted = [
            DefenseEnum::SSL,
            DefenseEnum::SWL,
            DefenseEnum::SSR,
            DefenseEnum::SWR,
            DefenseEnum::DUCK,
            DefenseEnum::REVERSAL,
            DefenseEnum::POWER_CRUSH,
            DefenseEnum::LOW_PARRY,
            DefenseEnum::PUNISH,
            DefenseEnum::THROW,
        ];

        usort(
            $this->values,
            function (DefenseEnum $defenseA, DefenseEnum $defenseB) use ($sorted) {
                return array_search($defenseA, $sorted, true) <=> array_search($defenseB, $sorted, true);
            }
        );

        return $this;
    }
}
