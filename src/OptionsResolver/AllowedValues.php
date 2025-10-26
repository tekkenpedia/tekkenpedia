<?php

declare(strict_types=1);

namespace App\OptionsResolver;

use Steevanb\PhpCollection\ScalarCollection\StringCollection;
use Symfony\Component\Console\Exception\InvalidOptionException;

class AllowedValues
{
    /** @param string[] $values */
    public static function isAllowed(array $values, StringCollection $allowedValues): bool
    {
        $invalidValues = array_diff($values, $allowedValues->toArray());
        if (count($invalidValues) > 0) {
            throw new InvalidOptionException(
                'Value "' . $invalidValues[0] . '" is invalid. Allowed values: '
                . implode(', ', $allowedValues->toArray()) . '.'
            );
        }

        return true;
    }
}
