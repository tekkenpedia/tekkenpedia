<?php

declare(strict_types=1);

namespace App\Callable;

use App\Exception\ShouldNotHappenException;

// @phpcs:ignores Squiz.Classes.ValidClassName.NotCamelCaps
class Callable_
{
    public static function create(object $object, string $method): callable
    {
        $return = [$object, $method];
        if (is_callable($return) === false) {
            throw new ShouldNotHappenException();
        }

        return $return;
    }
}
