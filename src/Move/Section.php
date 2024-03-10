<?php

declare(strict_types=1);

namespace App\Move;

use App\Collection\Move\MoveCollection;
use Symfony\Component\String\Slugger\AsciiSlugger;

readonly class Section
{
    public string $slug;

    public function __construct(public string $name, public MoveCollection $moves)
    {
        $this->slug = (new AsciiSlugger())->slug($this->name)->toString();
    }
}
