<?php

declare(strict_types=1);

namespace App\Character\Section;

use App\{
    Character\Move\Attack\AttackFactory,
    Character\Move\PowerCrush\PowerCrushFactory,
    Character\Move\Throw\ThrowFactory,
    Collection\Character\Move\MoveInterfaceCollection,
    Collection\Character\Move\SectionCollection,
    Exception\AppException,
    Parser\Character\Move\MoveTypeEnum
};

class SectionFactory
{
    /** @param TSection $section */
    public static function create(string $name, array &$section): Section
    {
        $sectionMoves = new MoveInterfaceCollection();
        /** @var string $moveId */
        foreach ($section['moves'] as $moveId => $moveData) {
            switch ($moveData['type']) {
                case MoveTypeEnum::ATTACK->name:
                    $sectionMoves->add(AttackFactory::create($moveId, $moveData));
                    break;
                case MoveTypeEnum::POWER_CRUSH->name:
                    $sectionMoves->add(PowerCrushFactory::create($moveId, $moveData));
                    break;
                case MoveTypeEnum::THROW->name:
                    $sectionMoves->add(ThrowFactory::create($moveId, $moveData));
                    break;
                default:
                    throw new AppException('Attack type "' . $moveData['type'] . '" is not taken into account.');
            }
        }
        $sectionMoves->setReadOnly();

        $sections = new SectionCollection();
        foreach ($section['sections'] as $subSectionName => $subSectionData) {
            $sections->add(static::create($subSectionName, $subSectionData));
        }
        $sections->setReadOnly();

        return new Section($name, $sectionMoves, $sections);
    }
}
