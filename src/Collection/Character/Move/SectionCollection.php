<?php

declare(strict_types=1);

namespace App\Collection\Character\Move;

use App\Character\Section\Section;
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

    /** @return array<Section> */
    public function toArray(): array
    {
        /** @var array<Section> $return */
        $return = parent::toArray();

        return $return;
    }
}
