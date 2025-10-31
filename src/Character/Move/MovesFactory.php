<?php

declare(strict_types=1);

namespace App\Character\Move;

use App\{
    Character\Section\SectionFactory,
    Collection\Character\Move\MoveInterfaceCollection,
    Collection\Character\Move\SectionCollection
};

class MovesFactory
{
    /** @param TCharacter $character */
    public static function create(array &$character, MoveInterfaceCollection $moves): SectionCollection
    {
        $sections = new SectionCollection();

        /**
         * @var string|int $sectionName Je n'ai pas trouvé pourquoi ce n'est pas pris en compte depuis TSections
         * @var TSection $sectionData
         */
        foreach ($character['sections'] as $sectionName => &$sectionData) {
            $sections->add(SectionFactory::create((string) $sectionName, $sectionData, $moves));
        }

        return $sections->setReadOnly();
    }
}
