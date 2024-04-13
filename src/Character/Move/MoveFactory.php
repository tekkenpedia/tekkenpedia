<?php

declare(strict_types=1);

namespace App\Character\Move;

use App\{
    Character\Move\Attack\Attack,
    Character\Move\Attack\Damages,
    Character\Move\Attack\Distances as AttackDistances,
    Character\Move\Attack\Frames as AttackFrames,
    Character\Move\Attack\PropertyEnum as AttackPropertyEnum,
    Character\Move\Comment\Comment,
    Character\Move\Comment\TypeEnum,
    Character\Move\Comment\WidthEnum,
    Character\Move\Step\StepEnum,
    Character\Move\Step\Steps,
    Character\Move\Throw\Distances as ThrowDistances,
    Character\Move\Throw\Frames as ThrowFrames,
    Character\Move\Throw\PropertyEnum as ThrowPropertyEnum,
    Character\Move\Throw\Throw_,
    Collection\Character\Move\BehaviorEnumCollection,
    Collection\Character\Move\CommentCollection,
    Collection\Character\Move\MoveInterfaceCollection,
    Collection\Character\Move\SectionCollection,
    Exception\AppException,
    Parser\Character\MoveTypeEnum
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

    private static function createSection(string $name, array $data): Section
    {
        $moves = new MoveInterfaceCollection();
        foreach ($data['moves'] as $moveName => $moveData) {
            switch ($moveData['type']) {
                case MoveTypeEnum::ATTACK->name:
                    $moves->add(static::createAttack($moveName, $moveData));
                    break;
                case MoveTypeEnum::THROW->name:
                    $moves->add(static::createThrow($moveName, $moveData));
                    break;
                default:
                    throw new AppException('Attack type "' . $moveData['type'] . '" is not taken into account.');
            }
        }
        $moves->setReadOnly();

        $sections = new SectionCollection();
        foreach ($data['sections'] as $subSectionName => $subSectionData) {
            $sections->add(static::createSection($subSectionName, $subSectionData));
        }
        $sections->setReadOnly();

        return new Section($name, $moves, $sections);
    }

    private static function createAttack(string $name, array $data): Attack
    {
        return new Attack(
            $name,
            $data['slug'],
            AttackPropertyEnum::create($data['property']),
            new AttackDistances(
                new MinMax($data['distances']['block']['min'], $data['distances']['block']['max']),
                new MinMax($data['distances']['normal-hit']['min'], $data['distances']['normal-hit']['max']),
                new MinMax($data['distances']['counter-hit']['min'], $data['distances']['counter-hit']['max'])
            ),
            new AttackFrames(
                $data['frames']['startup'],
                $data['frames']['normal-hit'],
                $data['frames']['counter-hit'],
                $data['frames']['block']
            ),
            new Damages($data['damages']['normal-hit'], $data['damages']['counter-hit']),
            static::createBehaviors($data['behaviors']['normal-hit']),
            static::createBehaviors($data['behaviors']['counter-hit']),
            new Steps(
                StepEnum::create($data['steps']['ssl']),
                StepEnum::create($data['steps']['swl']),
                StepEnum::create($data['steps']['ssr']),
                StepEnum::create($data['steps']['swr'])
            ),
            static::createComments($data)
        );
    }

    private static function createThrow(string $name, array $data): Throw_
    {
        return new Throw_(
            $name,
            $data['slug'],
            ThrowPropertyEnum::create($data['property']),
            new ThrowFrames(
                $data['frames']['startup'],
                $data['frames']['hit'],
                $data['frames']['escape'],
                $data['frames']['after-escape']
            ),
            new ThrowDistances(
                $data['distances']['startup'],
                $data['distances']['hit'],
                $data['distances']['escape']
            ),
            new StringCollection($data['escapes']),
            $data['damage'],
            static::createBehaviors($data['behaviors']),
            static::createComments($data)
        );
    }

    private static function createBehaviors(array &$behaviors): BehaviorEnumCollection
    {
        $return = new BehaviorEnumCollection();
        foreach ($behaviors as $behavior) {
            $return->add(BehaviorEnum::create($behavior));
        }

        return $return;
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
}
