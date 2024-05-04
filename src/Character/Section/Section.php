<?php

declare(strict_types=1);

namespace App\Character\Section;

use App\{
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

    public function hasDefenseMoves(): bool
    {
        return $this->moves->hasDefenseMoves() || $this->sections->hasDefenseMoves();
    }
}
