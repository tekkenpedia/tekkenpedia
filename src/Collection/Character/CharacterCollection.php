<?php

declare(strict_types=1);

namespace App\Collection\Character;

use App\Character\Character;
use Steevanb\PhpCollection\ObjectCollection\AbstractObjectCollection;

class CharacterCollection extends AbstractObjectCollection
{
    public function __construct()
    {
        parent::__construct(Character::class);
    }

    public function add(Character $move): static
    {
        return $this->doAdd($move);
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

    /** @return array<Character> */
    public function toArray(): array
    {
        return parent::toArray();
    }
}
