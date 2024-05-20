<?php

declare(strict_types=1);

namespace App\Character\Move\Attack;

use App\{
    Character\Move\Attack\Frame\Absorption,
    Character\Move\Attack\Frame\AfterAbsorption,
    Character\Move\Attack\Frame\Block,
    Character\Move\Attack\Frame\Frames,
    Character\Move\Attack\Frame\Startup,
    Character\Move\Behavior\BehaviorsFactory,
    Character\Move\Comment\CommentsFactory,
    Character\Move\Distance\MinMax,
    Character\Move\Step\StepEnum,
    Character\Move\Step\Steps,
    Character\Move\Visibility,
    Exception\AppException};

class AttackFactory
{
    public static function create(string $id, array &$attack, array &$moves): Attack
    {
        $parentAttack = is_string($attack['parent']) ? static::getParent($attack['parent'], $moves) : null;

        $slug = $attack['slug'] ?? $attack['name'];
        if ($attack['heat']) {
            $slug .= '_heat-activated';
        }

        return new Attack(
            $attack['master-id'],
            $id,
            $attack['name'],
            $slug,
            $attack['heat'],
            new Visibility($attack['visibility']['defense']),
            PropertyEnum::create(static::getData($attack, $parentAttack, 'property')),
            new PowerCrush(static::getData($attack, $parentAttack, 'power-crush', 'damage-reduction')),
            new Distances(
                $attack['distances']['startup'],
                new MinMax(
                    static::getData($attack, $parentAttack, 'distances', 'block', 'min'),
                    static::getData($attack, $parentAttack, 'distances', 'block', 'max')
                ),
                new MinMax(
                    static::getData($attack, $parentAttack, 'distances', 'normal-hit', 'min'),
                    static::getData($attack, $parentAttack, 'distances', 'normal-hit', 'max')
                ),
                new MinMax(
                    static::getData($attack, $parentAttack, 'distances', 'counter-hit', 'min'),
                    static::getData($attack, $parentAttack, 'distances', 'counter-hit', 'max')
                )
            ),
            new Frames(
                new Startup(
                    static::getData($attack, $parentAttack, 'frames', 'startup', 'min'),
                    static::getData($attack, $parentAttack, 'frames', 'startup', 'max')
                ),
                new Absorption(
                    static::getData($attack, $parentAttack, 'frames', 'absorption', 'min'),
                    static::getData($attack, $parentAttack, 'frames', 'absorption', 'max')
                ),
                new AfterAbsorption(static::getData($attack, $parentAttack, 'frames', 'after-absorption', 'block')),
                new Block($attack['frames']['block']['min'], $attack['frames']['block']['max']),
                $attack['frames']['normal-hit'],
                $attack['frames']['counter-hit']
            ),
            new Damages(
                static::getData($attack, $parentAttack, 'damages', 'normal-hit'),
                static::getData($attack, $parentAttack, 'damages', 'counter-hit')
            ),
            new Behaviors(
                BehaviorsFactory::create(static::getData($attack, $parentAttack, 'behaviors', 'block')),
                BehaviorsFactory::create(static::getData($attack, $parentAttack, 'behaviors', 'normal-hit')),
                BehaviorsFactory::create(static::getData($attack, $parentAttack, 'behaviors', 'counter-hit'))
            ),
            new Steps(
                StepEnum::create(static::getData($attack, $parentAttack, 'steps', 'ssl') ?? StepEnum::IMPOSSIBLE->name),
                StepEnum::create(static::getData($attack, $parentAttack, 'steps', 'swl') ?? StepEnum::IMPOSSIBLE->name),
                StepEnum::create(static::getData($attack, $parentAttack, 'steps', 'ssr') ?? StepEnum::IMPOSSIBLE->name),
                StepEnum::create(static::getData($attack, $parentAttack, 'steps', 'swr') ?? StepEnum::IMPOSSIBLE->name)
            ),
            CommentsFactory::create($attack['comments'])
        );
    }

    private static function getData(array &$attack, ?array &$parentAttack, string ...$keys): mixed
    {
        $attackDepth = $attack;
        foreach ($keys as $key) {
            $attackDepth = $attackDepth[$key] ?? null;
        }

        $parentDepth = $parentAttack;
        if (is_null($attackDepth) && is_array($parentAttack)) {
            foreach ($keys as $key) {
                $parentDepth = $parentDepth[$key] ?? null;
            }
        }

        return $attackDepth ?? $parentDepth;
    }

    private static function getParent(string $id, array &$moves): array
    {
        $return = null;

        foreach ($moves['moves'] as &$section) {
            foreach ($section['moves'] as $moveId => &$move) {
                if ($moveId === $id) {
                    $return = &$move;
                    break 2;
                }
            }
        }

        if (is_array($return) === false) {
            throw new AppException('Attack "' . $id . '" not found.');
        }

        return $return;
    }
}
