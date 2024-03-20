<?php

declare(strict_types=1);

namespace App\Character\Move;

use App\{
    Character\Move\Comment\Comment,
    Character\Move\Comment\TypeEnum,
    Character\Move\Comment\WidthEnum,
    Character\Move\Step\StepEnum,
    Character\Move\Step\Steps,
    Character\Move\Throw\BehaviorEnum,
    Character\Move\Throw\Distances,
    Character\Move\Throw\Frames as ThrowFrames,
    Character\Move\Throw\Throw_,
    Collection\Character\Move\CommentCollection,
    Collection\Character\Move\MoveCollection,
    Collection\Character\Move\SectionCollection,
    Collection\Character\Move\Throw\BehaviorEnumCollection,
    Collection\Character\Move\Throw\ThrowCollection
};
use Steevanb\PhpCollection\ScalarCollection\StringCollection;

class MoveFactory
{
    public static function create(array &$data): SectionCollection
    {
        $sections = new SectionCollection();

        foreach ($data['moves'] as $sectionName => $sectionData) {
            $sections->add(static::createSection($sectionName, $sectionData));
        }

        return $sections->setReadOnly();
    }

    private static function createThrow(string $name, array $data): Throw_
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
            new StringCollection($data['escapes']),
            $data['damage'],
            $behaviors,
            static::createComments($data)
        );
    }

    private static function createMove(string $name, array $data): Move
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
            static::createComments($data)
        );
    }

    private static function createComments(array $data): CommentCollection
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

    private static function createSection(string $name, array $data): Section
    {
        $throws = new ThrowCollection();
        foreach ($data['throws'] as $throwName => $throwData) {
            $throws->add(static::createThrow($throwName, $throwData));
        }
        $throws->setReadOnly();

        $moves = new MoveCollection();
        foreach ($data['moves'] as $moveName => $moveData) {
            $moves->add(static::createMove($moveName, $moveData));
        }
        $moves->setReadOnly();

        $sections = new SectionCollection();
        foreach ($data['sections'] as $subSectionName => $subSectionData) {
            $sections->add(static::createSection($subSectionName, $subSectionData));
        }
        $sections->setReadOnly();

        return new Section($name, $throws, $moves, $sections);
    }
}
