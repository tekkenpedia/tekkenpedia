<?php

declare(strict_types=1);

namespace App\Enum;

use App\Exception\AppException;

trait CreateTrait
{
    public static function create(string $name): static
    {
        foreach (static::cases() as $case) {
            if ($case->name === $name) {
                return $case;
            }
        }

        throw new AppException('Invalid value "' . $name . '" for enum ' . static::class . '.');
    }
}
