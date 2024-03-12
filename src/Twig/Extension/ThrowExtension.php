<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\Collection\Move\Throw\PropertyEnumCollection;
use App\Move\Throw\PropertyEnum;
use Twig\{
    Extension\AbstractExtension,
    TwigFilter
};
use Steevanb\PhpCollection\ScalarCollection\StringCollection;

class ThrowExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('throw_properties', [$this, 'throwProperties'])
        ];
    }

    public function throwProperties(PropertyEnumCollection $properties): string
    {
        $values = new StringCollection();
        foreach ($properties->toArray() as $property) {
            $values->add(
                match ($property) {
                    PropertyEnum::FLOOR_BOUNCE => 'Floor bounce',
                    PropertyEnum::FLOOR_BREAK => 'Floor break',
                    PropertyEnum::WALL_BOUNCE => 'Wall bounce',
                    PropertyEnum::WALL_SPLAT => 'Wall splat'
                }
            );
        }

        return implode(' - ', $values->toArray());
    }
}
