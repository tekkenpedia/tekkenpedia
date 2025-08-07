<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\Character\Section\Section;
use Twig\{
    Extension\AbstractExtension,
    TwigFilter,
};

class SectionExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('section_has_moves', $this->sectionHasMoves(...), ['is_safe' => ['html']])
        ];
    }

    public function sectionHasMoves(Section $section, string $pageType): bool
    {
        return match ($pageType) {
            'defense' => $section->hasDefenseMoves(),
            'punish' => $section->hasPunishMoves(),
            default => throw new \Exception('Unknown page type ' . $pageType . '.')
        };
    }
}
