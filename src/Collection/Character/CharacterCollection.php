<?php

declare(strict_types=1);

namespace App\Collection\Character;

use App\Character\Character;
use Steevanb\PhpCollection\ObjectCollection\AbstractObjectCollection;

/** @extends AbstractObjectCollection<Character> */
class CharacterCollection extends AbstractObjectCollection
{
    public static function getValueFqcn(): string
    {
        return Character::class;
    }

    public function findForSelectScreen(int $line, int $position): ?Character
    {
        $return = null;
        foreach ($this->toArray() as $character) {
            if ($character->selectScreen->line === $line && $character->selectScreen->position === $position) {
                $return = $character;
                break;
            }
        }

        return $return;
    }
}
