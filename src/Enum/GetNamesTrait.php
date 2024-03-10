<?php

declare(strict_types=1);

namespace App\Enum;

use Steevanb\PhpCollection\ScalarCollection\StringCollection;

trait GetNamesTrait
{
    public static function getNames(): StringCollection
    {
        $return = new StringCollection();
        foreach (static::cases() as $case) {
            $return->add($case->name);
        }

        return $return;
    }
}
