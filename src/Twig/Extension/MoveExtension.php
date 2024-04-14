<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\Character\Move\Behavior\BehaviorEnum;
use Twig\{
    Extension\AbstractExtension,
    TwigFilter};

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
            case BehaviorEnum::WALL_SPLAT_BREAK_BOUND:
                $icon = 'bi-box-arrow-in-right';
                $title = 'Wall splat - wall break - wall bound';
                $type = MoveBehaviorIconTypeEnum::I;
                break;
            case BehaviorEnum::FLOOR_BREAK_BLAST:
                $icon = 'bi-download';
                $title = 'Floor break - floor blast';
                $type = MoveBehaviorIconTypeEnum::I;
                break;
            case BehaviorEnum::KNOCKDOWN:
                $icon = 'bi-arrow-90deg-down vertical-symmetry';
                $title = 'Knockdown';
                $type = MoveBehaviorIconTypeEnum::I;
                break;
            case BehaviorEnum::AIR:
                $icon = 'bi-arrow-up-right';
                $title = 'Air';
                $type = MoveBehaviorIconTypeEnum::I;
                break;
            case BehaviorEnum::DELETE_RECOVERABLE_LIFE_BAR:
                $icon = 'delete-recoverable-life-bar';
                $title = 'Delete recoverable life bar';
                $type = MoveBehaviorIconTypeEnum::PNG;
                break;
            case BehaviorEnum::HEAT_ENGAGER:
                $icon = 'heat-engager';
                $title = 'Heat engager';
                $type = MoveBehaviorIconTypeEnum::PNG;
                break;
            case BehaviorEnum::POWER_CRUSH:
                $icon = 'power-crush';
                $title = 'Power crush';
                $type = MoveBehaviorIconTypeEnum::PNG;
                break;
        }

        return $type === MoveBehaviorIconTypeEnum::I
            ? '<i class="icon-move-behavior bi ' . $icon . '" title="' . $title . '"></i>'
            : '<img class="icon-move-behavior" src="../../../images/properties/' . $icon . '.png" title="' . $title . '">';

    }
}
