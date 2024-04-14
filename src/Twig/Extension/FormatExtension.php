<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\{
    Character\Move\Attack\PropertyEnum as AttackPropertyEnum,
    Character\Move\Distance\MinMax,
    Character\Move\Throw\Frames\Startup as ThrowFramesStartup,
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
            new TwigFilter('format_throw_startup_frames', [$this, 'formatThrowStartupFrames'], ['is_safe' => ['html']]),
            new TwigFilter('format_throw_property', [$this, 'formatThrowProperty']),
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

    public function formatThrowStartupFrames(ThrowFramesStartup $startup): ?string
    {
        $return = null;

        if (is_int($startup->min)) {
            $return .= $this->formatFrame($startup->min, true);
        }

        if (is_int($startup->max)) {
            if (is_string($return)) {
                $return .= '<i class="bi bi-arrows"></i>';
            }
            $return .= $this->formatFrame($startup->max, true);
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

    public function formatDistances(MinMax $distance): ?string
    {
        $return = null;

        if (is_int($distance->min)) {
            $return .= number_format($distance->min / 100, 2);
        }

        if (is_int($distance->max)) {
            if (is_string($return)) {
                $return .= '<i class="bi bi-arrows"></i>';
            }
            $return .= number_format($distance->max / 100, 2);
        }

        return $return;
    }
}
