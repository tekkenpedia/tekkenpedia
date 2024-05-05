<?php

declare(strict_types=1);

namespace App\Character\Move\PatchNote;

readonly class PatchNote
{
    public function __construct(
        public PatchNoteTypeEnum $type,
        public PatchNoteCategoryEnum $category,
        public ?string $summary,
        public string $description
    ) {
    }
}
