<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\{
    Character\Move\Behavior\BehaviorEnum,
    Character\Move\Comment\TypeEnum,
    Character\Move\Step\StepEnum,
    Character\Move\Throw\Frame\Hit\Hit as ThrowFramesHit,
    Character\Move\Throw\PropertyEnum,
    Collection\Character\Move\BehaviorEnumCollection
};
use Twig\{
    Extension\AbstractExtension,
    TwigFilter
};

class BgClassExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('frame_bg_class', [$this, 'frameBgClass']),
            new TwigFilter('step_bg_class', [$this, 'stepBgClass']),
            new TwigFilter('throw_hit_bg_class', [$this, 'throwHitBgClass']),
            new TwigFilter('throw_escape_bg_class', [$this, 'throwEscapeBgClass']),
            new TwigFilter('throw_property_bg_class', [$this, 'throwPropertyBgClass']),
            new TwigFilter('comment_type_bg_class', [$this, 'commentTypeBgClass'])
        ];
    }

    public function stepBgClass(StepEnum $step): string
    {
        return match ($step) {
            StepEnum::EASY => 'bg-success text-white',
            StepEnum::MEDIUM => 'bg-primary text-white',
            StepEnum::HARD => 'bg-warning text-white',
            StepEnum::IMPOSSIBLE => 'bg-danger text-white',
        };
    }

    public function commentTypeBgClass(TypeEnum $type): ?string
    {
        return match ($type) {
            TypeEnum::NORMAL => null,
            TypeEnum::DEFENSE => 'bg-success text-white',
            TypeEnum::STRENGTH => 'bg-danger text-white'
        };
    }

    public function throwHitBgClass(ThrowFramesHit $framesHit, BehaviorEnumCollection $behaviors): ?string
    {
        if ($framesHit->normal === 0 || $behaviors->contains(BehaviorEnum::AIR)) {
            $return = null;
        } elseif ($framesHit->normal < 0) {
            $return = 'bg-success text-white';
        } else {
            $return = 'bg-danger text-white';
        }

        return $return;
    }

    public function throwEscapeBgClass(int $frame): ?string
    {
        if ($frame === 0) {
            $return = null;
        } elseif ($frame < 0) {
            $return = 'bg-success text-white';
        } else {
            $return = 'bg-danger text-white';
        }

        return $return;
    }

    public function throwPropertyBgClass(PropertyEnum $property): ?string
    {
        return match ($property) {
            PropertyEnum::HIGH => null,
            PropertyEnum::MIDDLE, PropertyEnum::LOW => 'bg-warning'
        };
    }

    public function frameBgClass(?int $frame): ?string
    {
        if (is_int($frame) === false) {
            return null;
        }

        $return = 'text-white';
        if ($frame > 0) {
            $return .= ' bg-danger';
        } elseif ($frame < 0) {
            $return .= ' bg-success';
        } else {
            $return .= ' bg-warning';
        }

        return $return;
    }
}
