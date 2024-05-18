<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\{
    Character\Move\Attack\PropertyEnum as AttackPropertyEnum,
    Character\Move\Distance\MinMax,
    Character\Move\MinMaxFramesInterface,
    Character\Move\Throw\PropertyEnum as ThrowPropertyEnum
};
use Twig\{
    Extension\AbstractExtension,
    TwigFilter
};

class FormatExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('format_frame', [$this, 'formatFrame']),
            new TwigFilter('format_attack_property', [$this, 'formatAttackProperty']),
            new TwigFilter('format_min_max_frames', [$this, 'formatMinMaxFrames'], ['is_safe' => ['html']]),
            new TwigFilter('format_throw_property', [$this, 'formatThrowProperty']),
            new TwigFilter('format_distance', [$this, 'formatDistance'], ['is_safe' => ['html']]),
            new TwigFilter('format_distances', [$this, 'formatDistances'], ['is_safe' => ['html']])
        ];
    }

    public function formatAttackProperty(AttackPropertyEnum $property): string
    {
        return ucfirst(strtolower($property->name));
    }

    public function formatThrowProperty(ThrowPropertyEnum $property): string
    {
        return ucfirst(strtolower($property->name));
    }

    public function formatMinMaxFrames(MinMaxFramesInterface $minMaxFrames): ?string
    {
        $return = null;

        if (is_int($minMaxFrames->getMin())) {
            $return .= $this->formatFrame($minMaxFrames->getMin(), true);
        }

        if (is_int($minMaxFrames->getMax())) {
            if (is_string($return)) {
                $return .= '<i class="bi bi-arrows"></i>';
            }
            $return .= $this->formatFrame($minMaxFrames->getMax(), true);
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
