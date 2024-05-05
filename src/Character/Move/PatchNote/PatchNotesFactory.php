<?php

declare(strict_types=1);

namespace App\Character\Move\PatchNote;

use App\Collection\Character\Move\PatchNote\PatchNoteCollection;

class PatchNotesFactory
{
    /** @return array<PatchNoteCollection> */
    public static function create(array &$patchNotesData): array
    {
        $return = [];

        foreach ($patchNotesData as $patchNoteId => $patchNotes) {
            $return[$patchNoteId] = new PatchNoteCollection();

            foreach ($patchNotes as $patchNotesDatum) {
                $return[$patchNoteId]->add(
                    new PatchNote(
                        PatchNoteTypeEnum::create($patchNotesDatum['type']),
                        PatchNoteCategoryEnum::create($patchNotesDatum['category']),
                        $patchNotesDatum['summary'],
                        $patchNotesDatum['description']
                    )
                );
            }
        }

        return $return;
    }
}
