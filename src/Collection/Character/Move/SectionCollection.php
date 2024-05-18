<?php

declare(strict_types=1);

namespace App\Collection\Character\Move;

use App\Character\Section\Section;
use Steevanb\PhpCollection\ObjectCollection\AbstractObjectCollection;

/** @extends AbstractObjectCollection<Section> */
class SectionCollection extends AbstractObjectCollection
{
    public static function getValueFqcn(): string
    {
        return Section::class;
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
