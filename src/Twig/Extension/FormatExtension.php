<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\Character\Move\PropertyEnum;
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
            new TwigFilter('format_property', [$this, 'formatProperty'])
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
}
