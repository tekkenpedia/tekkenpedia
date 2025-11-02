<?php

declare(strict_types=1);

namespace App\Enum;

interface CreateInterface
{
    public static function create(string $name): static;
}
