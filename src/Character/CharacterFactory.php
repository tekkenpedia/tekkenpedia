<?php

declare(strict_types=1);

namespace App\Character;

use App\{
    Character\Move\MoveFactory,
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

    public function create(string $slug): Character
    {
        $jsonPathname = $this->charactersPath . '/' . $slug . '.json';
        $data = $this->jsonParser->getData($jsonPathname);

        return new Character($data['name'], $slug, MoveFactory::create($data));
    }

    public function createAll(): CharacterCollection
    {
        $return = new CharacterCollection();
        foreach ($this->getSlugs()->toArray() as $slug) {
            $return->add($this->create($slug));
        }

        return $return->setReadOnly();
    }

    private function getSlugs(): StringCollection
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
