<?php

declare(strict_types=1);

namespace App\PatchNote;

readonly class PatchNotes
{
    public function __construct(public string $id, public string $name, public string $releaseDates)
    {
    }
}
