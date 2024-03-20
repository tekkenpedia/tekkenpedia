<?php

declare(strict_types=1);

namespace App\Character;

use App\Collection\Character\Move\SectionCollection;

readonly class Character
{
    public function __construct(public string $name, public string $slug, public SectionCollection $sections)
    {
    }
}
