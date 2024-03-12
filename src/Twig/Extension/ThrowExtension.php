<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\{
    Collection\Move\Throw\BehaviorEnumCollection,
    Move\Throw\BehaviorEnum
};
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
            new TwigFilter('throw_behaviors', [$this, 'throwBehaviors'])
        ];
    }

    public function throwBehaviors(BehaviorEnumCollection $behaviors): string
    {
        $values = new StringCollection();
        foreach ($behaviors->toArray() as $behavior) {
            $values->add(
                match ($behavior) {
                    BehaviorEnum::FLOOR_BOUNCE => 'Floor bounce',
                    BehaviorEnum::FLOOR_BREAK => 'Floor break',
                    BehaviorEnum::WALL_BOUNCE => 'Wall bounce',
                    BehaviorEnum::WALL_SPLAT => 'Wall splat'
                }
            );
        }

        return implode(' - ', $values->toArray());
    }
}
