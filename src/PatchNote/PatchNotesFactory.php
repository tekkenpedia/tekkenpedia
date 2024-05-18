<?php

declare(strict_types=1);

namespace App\PatchNote;

use App\{
    Collection\PatchNote\PatchNotesCollection,
    Parser\PatchNote\JsonParser
};
use Symfony\Component\Finder\Finder;

readonly class PatchNotesFactory
{
    private string $patchNotesPath;

    public function __construct(string $projectDir, private JsonParser $jsonParser)
    {
        $this->patchNotesPath = $projectDir . '/data/patch-notes';
    }

    public function create(): PatchNotesCollection
    {
        $finder = (new Finder())
            ->in($this->patchNotesPath)
            ->name('*.json');

        $return = new PatchNotesCollection();
        foreach ($finder as $file) {
            $data = $this->jsonParser->parse($file->getPathname());

            $return->add(
                new PatchNotes(
                    $data['id'],
                    $data['name'],
                    $data['releaseDates']
                )
            );
        }

        return $return;
    }
}
