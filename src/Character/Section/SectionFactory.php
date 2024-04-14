<?php

declare(strict_types=1);

namespace App\Character\Section;

use App\{
    Character\Move\Attack\AttackFactory,
    Character\Move\Throw\ThrowFactory,
    Collection\Character\Move\MoveInterfaceCollection,
    Collection\Character\Move\SectionCollection,
    Exception\AppException,
    Parser\Character\Move\MoveTypeEnum
};

class SectionFactory
{
    public static function create(string $name, array &$section): Section
    {
        $moves = new MoveInterfaceCollection();
        foreach ($section['moves'] as $moveName => $moveData) {
            switch ($moveData['type']) {
                case MoveTypeEnum::ATTACK->name:
                    $moves->add(AttackFactory::create($moveName, $moveData));
                    break;
                case MoveTypeEnum::THROW->name:
                    $moves->add(ThrowFactory::create($moveName, $moveData));
                    break;
                default:
                    throw new AppException('Attack type "' . $moveData['type'] . '" is not taken into account.');
            }
        }
        $moves->setReadOnly();

        $sections = new SectionCollection();
        foreach ($section['sections'] as $subSectionName => $subSectionData) {
            $sections->add(static::create($subSectionName, $subSectionData));
        }
        $sections->setReadOnly();

        return new Section($name, $moves, $sections);
    }
}
