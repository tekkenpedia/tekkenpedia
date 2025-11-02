<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\{
    Character\Move\Attack\PropertyEnum as AttackPropertyEnum,
    Character\Move\PowerCrush\PropertyEnum as PowerCrushPropertyEnum,
    Character\Move\Throw\PropertyEnum as ThrowPropertyEnum,
    Character\Move\Distance\MinMax,
    Character\Move\MinMaxFramesInterface,
    Collection\Character\Move\Attack\PropertyEnumCollection as AttackPropertyEnumCollection,
    Collection\Character\Move\PowerCrush\PropertyEnumCollection as PowerCrushPropertyEnumCollection,
    Collection\Character\Move\Throw\PropertyEnumCollection as ThrowPropertyEnumCollection
};
use Twig\{
    Extension\AbstractExtension,
    TwigFilter
};
use Steevanb\PhpCollection\ScalarCollection\StringCollection;

class FormatExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('format_frame', $this->formatFrame(...)),
            new TwigFilter('format_move_properties', $this->formatMoveProperties(...), ['is_safe' => ['html']]),
            new TwigFilter('format_min_max_frames', $this->formatMinMaxFrames(...), ['is_safe' => ['html']]),
            new TwigFilter('format_distance', $this->formatDistance(...), ['is_safe' => ['html']]),
            new TwigFilter('format_distances', $this->formatDistances(...), ['is_safe' => ['html']])
        ];
    }

    public function formatMoveProperties(
        AttackPropertyEnumCollection|PowerCrushPropertyEnumCollection|ThrowPropertyEnumCollection $properties
    ): string {
        $propertyLabels = new StringCollection();
        foreach ($properties->toArray() as $property) {
            $propertyLabels->add(
                match ($property) {
                    AttackPropertyEnum::HIGH, PowerCrushPropertyEnum::HIGH, ThrowPropertyEnum::HIGH
                        => '<span title="High">h</span>',
                    AttackPropertyEnum::MIDDLE, PowerCrushPropertyEnum::MIDDLE, ThrowPropertyEnum::MIDDLE
                        => '<span title="Middle">m</span>',
                    AttackPropertyEnum::SPECIAL_MIDDLE => '<span title="Special middle">sm</span>',
                    AttackPropertyEnum::LOW, ThrowPropertyEnum::LOW => '<span title="Low">l</span>',
                    AttackPropertyEnum::SPECIAL_LOW => '<span title="Special low">sl</span>'
                }
            );
        }

        return implode(',', $propertyLabels->toArray());
    }

    public function formatMinMaxFrames(MinMaxFramesInterface $minMaxFrames, bool $absolute = true): ?string
    {
        $return = null;

        if (is_int($minMaxFrames->getMin())) {
            $return .= $this->formatFrame($minMaxFrames->getMin(), $absolute);
        }

        if (is_int($minMaxFrames->getMax())) {
            if (is_string($return)) {
                $return .= '<i class="bi bi-arrows"></i>';
            }
            $return .= $this->formatFrame($minMaxFrames->getMax(), $absolute);
        }

        return $return;
    }

    public function formatFrame(int $frame, bool $absolute = false): string
    {
        if ($absolute || $frame <= 0) {
            $return = (string) $frame;
        } else {
            $return = '+' . $frame;
        }

        return $return;
    }

    public function formatDistance(int $distance): string
    {
        return number_format($distance / 100, 2);
    }

    public function formatDistances(MinMax $distance): ?string
    {
        $return = null;

        if (is_int($distance->min)) {
            $return .= $this->formatDistance($distance->min);
        }

        if (is_int($distance->max)) {
            if (is_string($return)) {
                $return .= '<i class="bi bi-arrows"></i>';
            }
            $return .= $this->formatDistance($distance->max);
        }

        return $return;
    }
}
