<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\{
    Character\Character,
    Character\Section\Section,
    Exception\AppException
};
use Twig\{
    Extension\AbstractExtension,
    TwigFilter,
};

class SectionExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('section_has_moves', $this->sectionHasMoves(...), ['is_safe' => ['html']]),
            new TwigFilter('sections_to_json', $this->sectionsToJson(...))
        ];
    }

    public function sectionHasMoves(Section $section, string $pageType): bool
    {
        return match ($pageType) {
            'defense' => $section->hasDefenseMoves(),
            'punish' => $section->hasPunishMoves(),
            default => throw new AppException('Unknown page type ' . $pageType . '.')
        };
    }

    public function sectionsToJson(Character $character): string
    {
        $data = [];
        foreach ($character->sections->toArray() as $section) {
            $this->recursiveAddSection($data, $section);
        }

        return json_encode($data, JSON_THROW_ON_ERROR);
    }

    /** @param array<mixed> $data */
    private function recursiveAddSection(array &$data, Section $section, ?string $prefix = null): static
    {
        $data[] = $this->getSectionName($section, $prefix);
        $prefix = $section->name;

        foreach ($section->sections->toArray() as $subSection) {
            $sectionName = $this->getSectionName($subSection, $prefix);
            $data[] = $sectionName;

            foreach ($subSection->sections->toArray() as $subSubSection) {
                $this->recursiveAddSection($data, $subSubSection, $sectionName);
            }
        }

        return $this;
    }

    private function getSectionName(Section $section, ?string $prefix = null): string
    {
        return (is_string($prefix) ? $prefix . ' - ' . $section->name : $section->name);
    }
}
