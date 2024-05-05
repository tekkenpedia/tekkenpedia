<?php

declare(strict_types=1);

namespace App\Collection\PatchNote;

use App\PatchNote\PatchNotes;
use Steevanb\PhpCollection\ObjectCollection\AbstractObjectCollection;

class PatchNotesCollection extends AbstractObjectCollection
{
    public function __construct()
    {
        parent::__construct(PatchNotes::class);
    }

    public function add(PatchNotes $patchNotes): static
    {
        return $this->doAdd($patchNotes);
    }

    /** @return array<PatchNotes> */
    public function toArray(): array
    {
        return parent::toArray();
    }
}
