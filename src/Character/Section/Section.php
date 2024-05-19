<?php

declare(strict_types=1);

namespace App\Character\Section;

use App\{
    Character\Move\Attack\Attack,
    Collection\Character\Move\MoveInterfaceCollection,
    Collection\Character\Move\SectionCollection
};
use Symfony\Component\String\Slugger\AsciiSlugger;

readonly class Section
{
    public string $slug;

    public function __construct(
        public string $name,
        public MoveInterfaceCollection $moves,
        public SectionCollection $sections
    ) {
        $this->slug = (new AsciiSlugger())->slug($this->name)->toString();
    }

    public function findAttack(string $id): ?Attack
    {
        $return = null;

        foreach ($this->moves->toArray() as $move) {
            if ($move instanceof Attack && $move->id === $id) {
                $return = $move;
                break;
            }
        }

        if ($return instanceof Attack === false) {
            foreach ($this->sections->toArray() as $section) {
                $return = $section->findAttack($id);
                if ($return instanceof Attack) {
                    break;
                }
            }
        }

        return $return;
    }

    public function hasDefenseMoves(): bool
    {
        return $this->moves->hasDefenseMoves() || $this->sections->hasDefenseMoves();
    }
}
