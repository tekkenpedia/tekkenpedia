<?php

declare(strict_types=1);

namespace App\Collection\Move;

use App\Character\Move\Section;
use Steevanb\PhpCollection\ObjectCollection\AbstractObjectCollection;

class SectionCollection extends AbstractObjectCollection
{
    public function __construct()
    {
        parent::__construct(Section::class);
    }

    public function add(Section $section): static
    {
        return $this->doAdd($section);
    }
}
