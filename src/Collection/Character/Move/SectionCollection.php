<?php

declare(strict_types=1);

namespace App\Collection\Character\Move;

use App\{
    Character\Move\Attack\Attack,
    Character\Section\Section,
    Exception\AppException
};
use Steevanb\PhpCollection\ObjectCollection\AbstractObjectCollection;

/** @extends AbstractObjectCollection<Section> */
class SectionCollection extends AbstractObjectCollection
{
    public static function getValueFqcn(): string
    {
        return Section::class;
    }

    public function getAttack(string $id): Attack
    {
        foreach ($this->toArray() as $section) {
            $attack = $section->findAttack($id);
            if ($attack instanceof Attack) {
                return $attack;
            }
        }

        throw new AppException('Attack ' . $id . ' not found.');
    }

    public function hasDefenseMoves(): bool
    {
        $return = false;

        foreach ($this->toArray() as $section) {
            if ($section->hasDefenseMoves()) {
                $return = true;
                break;
            }
        }

        return $return;
    }
}
