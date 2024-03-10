<?php

declare(strict_types=1);

namespace App\Move\Comment;

use Steevanb\PhpCollection\ScalarCollection\IntegerCollection;

enum WidthEnum: int
{
    case ONE = 1;
    case TWO = 2;
    case THREE = 3;
    case FOUR = 4;

    public static function getValues(): IntegerCollection
    {
        $return = new IntegerCollection();
        foreach (static::cases() as $case) {
            $return->add($case->value);
        }

        return $return;
    }
}
