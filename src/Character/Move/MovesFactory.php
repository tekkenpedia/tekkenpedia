<?php

declare(strict_types=1);

namespace App\Character\Move;

use App\{
    Character\Move\Attack\Attack,
    Character\Section\SectionFactory,
    Collection\Character\Move\Attack\AttackCollection,
    Collection\Character\Move\SectionCollection,
    Exception\ShouldNotHappenException
};

class MovesFactory
{
    /** @param TCharacter $character */
    public static function create(array &$character): SectionCollection
    {
        $sections = new SectionCollection();

        foreach ($character['sections'] as $sectionName => &$sectionData) {
            $sections->add(SectionFactory::create($sectionName, $sectionData));
        }

        static::configureSlavesAttacks($sections);

        return $sections->setReadOnly();
    }

    private static function configureSlavesAttacks(SectionCollection $sections): void
    {
        $slaveAttacks = new AttackCollection();
        static::fillSlavesAttacks($sections, $slaveAttacks);

        $masterAttacks = new AttackCollection();
        foreach ($slaveAttacks->toArray() as $slaveAttack) {
            if (is_string($slaveAttack->masterId) === false) {
                throw new ShouldNotHappenException();
            }

            $attack = $sections->getAttack($slaveAttack->masterId);
            $attack->slaves->add($slaveAttack);
            $slaveAttack->setMaster($attack);
            $masterAttacks->add($attack);
        }

        foreach ($masterAttacks->toArray() as $masterAttack) {
            $masterAttack->slaves->setReadOnly();
        }
    }

    private static function fillSlavesAttacks(SectionCollection $sections, AttackCollection $slaveAttacks): void
    {
        foreach ($sections->toArray() as $section) {
            foreach ($section->moves->toArray() as $move) {
                if ($move instanceof Attack && is_string($move->masterId)) {
                    $slaveAttacks->add($move);
                }
            }

            static::fillSlavesAttacks($section->sections, $slaveAttacks);
        }
    }
}
