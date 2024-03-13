<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\Move\Throw\BehaviorEnum;
use Twig\{
    Extension\AbstractExtension,
    TwigFilter
};

class ThrowExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('throw_behavior', [$this, 'throwBehavior'])
        ];
    }

    public function throwBehavior(BehaviorEnum $behavior): string
    {
        return match ($behavior) {
            BehaviorEnum::FLOOR_BOUNCE => 'Floor bounce.',
            BehaviorEnum::FLOOR_BREAK => 'Floor break.',
            BehaviorEnum::WALL_BOUNCE => 'Wall bounce.',
            BehaviorEnum::WALL_SPLAT => 'Wall splat.',
            BehaviorEnum::AIR => 'Air.'
        };
    }
}
