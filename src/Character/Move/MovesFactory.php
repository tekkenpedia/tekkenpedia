<?php

declare(strict_types=1);

namespace App\Character\Move;

use App\{
    Character\Section\SectionFactory,
    Collection\Character\Move\SectionCollection,
};

class MovesFactory
{
    public static function create(array &$moves): SectionCollection
    {
        $sections = new SectionCollection();

        foreach ($moves['moves'] as $sectionName => &$sectionData) {
            $sections->add(SectionFactory::create($sectionName, $sectionData, $moves));
        }

        return $sections->setReadOnly();
    }
}
