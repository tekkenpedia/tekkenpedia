<?php

declare(strict_types=1);

namespace App\Move;

use App\{
    Collection\Move\CommentCollection,
    Collection\Move\MoveCollection,
    Collection\Move\SectionCollection,
    Collection\Move\Throw\BehaviorEnumCollection,
    Collection\Move\Throw\ThrowCollection,
    Move\Comment\Comment,
    Move\Comment\TypeEnum,
    Move\Comment\WidthEnum,
    Move\Throw\Distances,
    Move\Throw\Frames as ThrowFrames,
    Move\Throw\BehaviorEnum,
    Move\Throw\Throw_
};
use Steevanb\PhpCollection\{
    ScalarCollection\IntegerCollection,
    ScalarCollection\StringCollection
};
use Symfony\Component\Finder\Finder;

class Moves
{
    private readonly string $movesPath;

    public function __construct(string $projectDir, private readonly JsonParser $jsonParser)
    {
        $this->movesPath = $projectDir . '/data/moves';
    }

    public function getCharactersSlugs(): StringCollection
    {
        $files = (new Finder())
            ->in($this->movesPath)
            ->files()
            ->name('*.json');

        $return = new StringCollection();
        foreach ($files as $file) {
            $return->add($file->getBasename('.json'));
        }

        return $return;
    }

    public function getSections(string $characterSlug): SectionCollection
    {
        $return = new SectionCollection();
        $jsonPathname = $this->movesPath . '/' . $characterSlug . '.json';

        foreach ($this->jsonParser->getData($jsonPathname) as $sectionName => $sectionData) {
            $return->add($this->createSection($sectionName, $sectionData));
        }

        return $return->setReadOnly();
    }

    private function createThrow(string $name, array $data): Throw_
    {
        $behaviors = new BehaviorEnumCollection();
        foreach ($data['behaviors'] as $behavior) {
            $behaviors->add(BehaviorEnum::create($behavior));
        }

        return new Throw_(
            $name,
            PropertyEnum::create($data['property']),
            new ThrowFrames(
                $data['frames']['startup'],
                $data['frames']['hit'],
                $data['frames']['escape'],
                $data['frames']['after-escape']
            ),
            new Distances(
                $data['distances']['startup'],
                $data['distances']['hit'],
                $data['distances']['escape']
            ),
            new IntegerCollection($data['escapes']),
            $data['damage'],
            $behaviors,
            $this->createComments($data)
        );
    }

    private function createMove(string $name, array $data): Move
    {
        return new Move(
            $name,
            PropertyEnum::create($data['property']),
            $data['distance'],
            new Frames(
                $data['frames']['startup'],
                $data['frames']['normal-hit'],
                $data['frames']['counter-hit'],
                $data['frames']['block']
            ),
            new Damages($data['damages']['normal-hit'], $data['damages']['counter-hit']),
            new Hits(
                HitEnum::create($data['hits']['normal']),
                HitEnum::create($data['hits']['counter'])
            ),
            new Steps(
                StepEnum::create($data['steps']['ssl']),
                StepEnum::create($data['steps']['swl']),
                StepEnum::create($data['steps']['ssr']),
                StepEnum::create($data['steps']['swr'])
            ),
            $this->createComments($data)
        );
    }

    private function createComments(array $data): CommentCollection
    {
        $return = new CommentCollection();
        foreach ($data['comments'] as $commentData) {
            $return->add(
                new Comment(
                    $commentData['comment'],
                    TypeEnum::create($commentData['type']),
                    WidthEnum::from($commentData['width'])
                )
            );
        }

        return $return;
    }

    private function createSection(string $name, array $data): Section
    {
        $throws = new ThrowCollection();
        foreach ($data['throws'] as $throwName => $throwData) {
            $throws->add($this->createThrow($throwName, $throwData));
        }
        $throws->setReadOnly();

        $moves = new MoveCollection();
        foreach ($data['moves'] as $moveName => $moveData) {
            $moves->add($this->createMove($moveName, $moveData));
        }
        $moves->setReadOnly();

        $sections = new SectionCollection();
        foreach ($data['sections'] as $subSectionName => $subSectionData) {
            $sections->add($this->createSection($subSectionName, $subSectionData));
        }
        $sections->setReadOnly();

        return new Section($name, $throws, $moves, $sections);
    }
}
