<?php

declare(strict_types=1);

namespace App\Character\Section;

use App\{
    Collection\Character\Move\MoveInterfaceCollection,
    Collection\Character\Move\SectionCollection
};

class SectionFactory
{
    /** @param TSection $section */
    public static function create(string $name, array &$section, MoveInterfaceCollection $moves): Section
    {
        $sections = new SectionCollection();
        foreach ($section['sections'] as $subSectionName => $subSectionData) {
            $sections->add(static::create($subSectionName, $subSectionData, $moves));
        }
        $sections->setReadOnly();

        $sectionMoves = new MoveInterfaceCollection();
        foreach ($section['moves'] as $moveId) {
            $sectionMoves->add($moves->getById($moveId));
        }

        return new Section($name, $sectionMoves, $sections);
    }
}
