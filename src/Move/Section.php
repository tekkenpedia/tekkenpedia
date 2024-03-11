<?php

declare(strict_types=1);

namespace App\Move;

use App\{
    Collection\Move\MoveCollection,
    Collection\Move\SectionCollection
};
use Symfony\Component\String\Slugger\AsciiSlugger;

readonly class Section
{
    public string $slug;

    public function __construct(public string $name, public MoveCollection $moves, public SectionCollection $sections)
    {
        $this->slug = (new AsciiSlugger())->slug($this->name)->toString();
    }
}
