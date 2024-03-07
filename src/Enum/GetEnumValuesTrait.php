<?php

declare(strict_types=1);

namespace App\Enum;

use Steevanb\PhpCollection\ScalarCollection\StringCollection;

trait GetEnumValuesTrait
{
    public static function getValues(): StringCollection
    {
        $return = new StringCollection();
        foreach (static::cases() as $case) {
            $return->add($case->name);
        }

        return $return;
    }
}
