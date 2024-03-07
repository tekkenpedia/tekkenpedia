<?php

declare(strict_types=1);

namespace App\Enum;

trait CreateTrait
{
    public static function create(string $name): static
    {
        foreach (static::cases() as $case) {
            if ($case->name === $name) {
                return $case;
            }
        }

        throw new \Exception('Invalid value "' . $name . '" for enum ' . static::class . '.');
    }
}
