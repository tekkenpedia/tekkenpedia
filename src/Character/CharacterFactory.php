<?php

declare(strict_types=1);

namespace App\Character;

use App\{
    Character\Move\MovesFactory,
    Collection\Character\CharacterCollection,
    Parser\Character\JsonParser
};
use Steevanb\PhpCollection\ScalarCollection\StringCollection;
use Symfony\Component\Finder\Finder;

readonly class CharacterFactory
{
    private string $charactersPath;

    public function __construct(string $projectDir, private JsonParser $jsonParser)
    {
        $this->charactersPath = $projectDir . '/data/characters';
    }

    public function create(string $fileName): Character
    {
        $jsonPathname = $this->charactersPath . '/' . $fileName . '.json';
        $data = $this->jsonParser->getData($jsonPathname);

        return new Character(
            $data['name'],
            $data['slug'],
            new SelectScreen($data['select-screen']['line'], $data['select-screen']['position']),
            MovesFactory::create($data)
        );
    }

    public function createAll(): CharacterCollection
    {
        $return = new CharacterCollection();
        foreach ($this->getFileNames()->toArray() as $fileName) {
            $return->add($this->create($fileName));
        }

        return $return->setReadOnly();
    }

    private function getFileNames(): StringCollection
    {
        $files = (new Finder())
            ->in($this->charactersPath)
            ->files()
            ->name('*.json');

        $return = new StringCollection();
        foreach ($files as $file) {
            $return->add($file->getBasename('.json'));
        }

        return $return;
    }
}
