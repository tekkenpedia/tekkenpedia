<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\{
    Character\Move\MinMax,
    Character\Move\PropertyEnum
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
            new TwigFilter('format_property', [$this, 'formatProperty']),
            new TwigFilter('format_distances', [$this, 'formatDistances'], ['is_safe' => ['html']])
        ];
    }

    public function formatProperty(PropertyEnum $property): string
    {
        return ucfirst(strtolower($property->name));
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
