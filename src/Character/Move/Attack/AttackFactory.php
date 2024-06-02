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
        $extends = is_string($attack['extends']) ? static::getExtends($attack['extends'], $moves) : null;

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
            PropertyEnum::create(static::getData($attack, $extends, 'property')),
            new PowerCrush(static::getData($attack, $extends, 'power-crush', 'damage-reduction')),
            new Distances(
                $attack['distances']['range'],
                new MinMax(
                    static::getData($attack, $extends, 'distances', 'block', 'min'),
                    static::getData($attack, $extends, 'distances', 'block', 'max')
                ),
                new MinMax(
                    static::getData($attack, $extends, 'distances', 'normal-hit', 'min'),
                    static::getData($attack, $extends, 'distances', 'normal-hit', 'max')
                ),
                new MinMax(
                    static::getData($attack, $extends, 'distances', 'counter-hit', 'min'),
                    static::getData($attack, $extends, 'distances', 'counter-hit', 'max')
                )
            ),
            new Frames(
                new Startup(
                    static::getData($attack, $extends, 'frames', 'startup', 'min'),
                    static::getData($attack, $extends, 'frames', 'startup', 'max')
                ),
                new Absorption(
                    static::getData($attack, $extends, 'frames', 'absorption', 'min'),
                    static::getData($attack, $extends, 'frames', 'absorption', 'max')
                ),
                new AfterAbsorption(static::getData($attack, $extends, 'frames', 'after-absorption', 'block')),
                new Block($attack['frames']['block']['min'], $attack['frames']['block']['max']),
                $attack['frames']['normal-hit'],
                $attack['frames']['counter-hit']
            ),
            new Damages(
                static::getData($attack, $extends, 'damages', 'normal-hit'),
                static::getData($attack, $extends, 'damages', 'counter-hit')
            ),
            new Behaviors(
                BehaviorsFactory::create(static::getData($attack, $extends, 'behaviors', 'block')),
                BehaviorsFactory::create(static::getData($attack, $extends, 'behaviors', 'normal-hit')),
                BehaviorsFactory::create(static::getData($attack, $extends, 'behaviors', 'counter-hit'))
            ),
            new Steps(
                is_string(static::getData($attack, $extends, 'steps', 'ssl'))
                    ? StepEnum::create(static::getData($attack, $extends, 'steps', 'ssl'))
                    : null,
                is_string(static::getData($attack, $extends, 'steps', 'swl'))
                    ? StepEnum::create(static::getData($attack, $extends, 'steps', 'swl'))
                    : null,
                is_string(static::getData($attack, $extends, 'steps', 'ssr'))
                    ? StepEnum::create(static::getData($attack, $extends, 'steps', 'ssr'))
                    : null,
                is_string(static::getData($attack, $extends, 'steps', 'swr'))
                    ? StepEnum::create(static::getData($attack, $extends, 'steps', 'swr'))
                    : null,
            ),
            CommentsFactory::create($attack['comments'])
        );
    }

    private static function getData(array &$attack, ?array &$extends, string ...$keys): mixed
    {
        $attackDepth = $attack;
        foreach ($keys as $key) {
            $attackDepth = $attackDepth[$key] ?? null;
        }

        $extendsDepth = $extends;
        if (is_null($attackDepth) && is_array($extends)) {
            foreach ($keys as $key) {
                $extendsDepth = $extendsDepth[$key] ?? null;
            }
        }

        return $attackDepth ?? $extendsDepth;
    }

    private static function getExtends(string $id, array &$moves): array
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
