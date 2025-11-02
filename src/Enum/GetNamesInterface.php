<?php

declare(strict_types=1);

namespace App\Enum;

use Steevanb\PhpCollection\ScalarCollection\StringCollection;

interface GetNamesInterface
{
    public static function getNames(): StringCollection;
}
