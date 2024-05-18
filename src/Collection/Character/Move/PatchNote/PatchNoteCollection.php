<?php

declare(strict_types=1);

namespace App\Collection\Character\Move\PatchNote;

use App\Character\Move\PatchNote\PatchNote;
use Steevanb\PhpCollection\ObjectCollection\AbstractObjectCollection;

class PatchNoteCollection extends AbstractObjectCollection
{
    public function __construct()
    {
        parent::__construct(PatchNote::class);
    }

    public function add(PatchNote $patchNotes): static
    {
        return $this->doAdd($patchNotes);
    }
}
