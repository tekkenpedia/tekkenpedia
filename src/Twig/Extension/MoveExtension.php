<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\Character\Move\Throw\BehaviorEnum;
use Twig\{
    Extension\AbstractExtension,
    TwigFilter
};

class MoveExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('move_behavior_icon', [$this, 'moveBehaviorIcon'], ['is_safe' => ['html']])
        ];
    }

    public function moveBehaviorIcon(BehaviorEnum $behavior): string
    {
        switch ($behavior) {
            case BehaviorEnum::WALL_BOUND:
                $icon = 'wall-bound';
                $title = 'Wall bound';
                break;
            case BehaviorEnum::WALL_BREAK:
                $icon = 'wall-break';
                $title = 'Wall break';
                break;
            case BehaviorEnum::WALL_SPLAT:
                $icon = 'wall-splat';
                $title = 'Wall splat';
                break;
            case BehaviorEnum::FLOOR_BREAK:
                $icon = 'floor-break';
                $title = 'Floor break';
                break;
            case BehaviorEnum::FLOOR_BLAST:
                $icon = 'floor-blast';
                $title = 'Floor blast';
                break;
            case BehaviorEnum::AIR:
                $icon = 'air';
                $title = 'Air';
                break;
            case BehaviorEnum::DELETE_RECOVERABLE_LIFE_BAR:
                $icon = 'delete-recoverable-life-bar';
                $title = 'Delete recoverable life bar';
                break;
            case BehaviorEnum::HEAT_ENGAGER:
                $icon = 'heat-engager';
                $title = 'Heat engager';
                break;
            case BehaviorEnum::POWER_CRUSH:
                $icon = 'power-crush';
                $title = 'Power crush';
                break;
        }

        return '<img class="icon-move-property" src="../../../images/properties/' . $icon . '.png" title="' . $title . '">';
    }
}
