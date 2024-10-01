<?php

declare(strict_types=1);

namespace App\Character;

use App\{
    Character\Move\MoveFactory,
    Character\Move\MovesFactory,
    Collection\Character\CharacterCollection,
    Collection\Character\Move\MoveInterfaceCollection,
    Collection\Symfony\Component\Finder\SplFileInfoCollection,
    Decoder\JsonDecoder,
    Parser\Character\CharacterOptionsResolver,
    Parser\Character\Move\MoveOptionsResolverFactory
};
use Steevanb\PhpCollection\ScalarCollection\StringCollection;
use Symfony\Component\Finder\Finder;

class CharacterFactory
{
    private string $charactersPath;

    private ?CharacterCollection $characters = null;

    public function __construct(string $projectDir)
    {
        $this->charactersPath = $projectDir . '/data/characters';
    }

    public function create(string $slug): Character
    {
        // DEBUG
        $slug = 'alisa-bosconovitch';
        // /DEBUG

        $moves = new MoveInterfaceCollection();
        foreach ($this->getMoveFiles($slug)->toArray() as $moveFile) {
            $moveJsonData = JsonDecoder::decode($moveFile->getPathname());
            $moveData = MoveOptionsResolverFactory::create($moveJsonData)->resolve($moveJsonData);
            $moves->add(MoveFactory::create($moveFile->getBasename('.json'), $moveData));
        }

        $characterJsonData = JsonDecoder::decode($this->charactersPath . '/' . $slug . '/character.json');
        $characterData = (new CharacterOptionsResolver($characterJsonData))->resolve($characterJsonData);

        return new Character(
            $characterData['name'],
            $characterData['slug'],
            new SelectScreen($characterData['select-screen']['line'], $characterData['select-screen']['position']),
            MovesFactory::create($characterData),
            $moves
        );
    }

    public function createAll(): CharacterCollection
    {
        if ($this->characters instanceof CharacterCollection === false) {
            $this->characters = new CharacterCollection();
            foreach ($this->getCharactersSlugs()->toArray() as $characterSlug) {
                $this->characters->add($this->create($characterSlug));
            }

            $this->characters->setReadOnly();
        }

        return $this->characters;
    }

    private function getCharactersSlugs(): StringCollection
    {
        $directories = (new Finder())
            ->in($this->charactersPath)
            ->directories();

        $return = new StringCollection();
        foreach ($directories as $directory) {
            $return->add($directory->getBasename());
        }

        return $return;
    }

    private function getMoveFiles(string $slug): SplFileInfoCollection
    {
        $return = new SplFileInfoCollection();

        if (is_dir($this->charactersPath . '/' . $slug . '/moves')) {
            $files = (new Finder())
                ->in($this->charactersPath . '/' . $slug . '/moves')
                ->files()
                ->name('*.json');

            foreach ($files as $file) {
                $return->add($file);
            }
        }

        return $return;
    }
}
