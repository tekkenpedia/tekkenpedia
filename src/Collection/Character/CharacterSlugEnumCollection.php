<?php

declare(strict_types=1);

namespace App\Collection\Character;

use App\Character\CharacterSlugEnum;
use Steevanb\PhpCollection\ObjectCollection\AbstractObjectCollection;

/** @extends AbstractObjectCollection<CharacterSlugEnum> */
class CharacterSlugEnumCollection extends AbstractObjectCollection
{
    public static function getValueFqcn(): string
    {
        return CharacterSlugEnum::class;
    }
}
