<?php

declare(strict_types=1);

namespace App\Character\Move\PowerCrush;

use App\{
    Character\Move\PowerCrush\Frame\Absorption,
    Character\Move\PowerCrush\Frame\AfterAbsorption,
    Character\Move\PowerCrush\Frame\Block,
    Character\Move\PowerCrush\Frame\Frames,
    Character\Move\PowerCrush\Frame\Startup,
    Character\Move\Behavior\BehaviorsFactory,
    Character\Move\Comment\CommentsFactory,
    Character\Move\Distance\MinMax,
    Character\Move\Step\StepEnum,
    Character\Move\Step\Steps,
    Character\Move\Visibility,
    Exception\AppException
};

class PowerCrushFactory
{
    public static function create(string $id, array &$powerCrush, array &$moves): PowerCrush
    {
        $extends = is_string($powerCrush['extends']) ? static::getExtends($powerCrush['extends'], $moves) : null;

        $slug = $powerCrush['slug'] ?? $powerCrush['inputs'];
        if ($powerCrush['heat']) {
            $slug .= '_heat-activated';
        }

        return new PowerCrush(
            $powerCrush['master'],
            $id,
            $powerCrush['inputs'],
            $powerCrush['situation'],
            $slug,
            $powerCrush['heat'],
            new Visibility($powerCrush['visibility']['defense']),
            PropertyEnum::create(static::getData($powerCrush, $extends, 'property')),
            static::getData($powerCrush, $extends, 'damage-reduction'),
            new Distances(
                $powerCrush['distances']['range'],
                new MinMax(
                    static::getData($powerCrush, $extends, 'distances', 'block', 'min'),
                    static::getData($powerCrush, $extends, 'distances', 'block', 'max')
                ),
                new MinMax(
                    static::getData($powerCrush, $extends, 'distances', 'normal-hit', 'min'),
                    static::getData($powerCrush, $extends, 'distances', 'normal-hit', 'max')
                ),
                new MinMax(
                    static::getData($powerCrush, $extends, 'distances', 'counter-hit', 'min'),
                    static::getData($powerCrush, $extends, 'distances', 'counter-hit', 'max')
                )
            ),
            new Frames(
                new Startup(
                    static::getData($powerCrush, $extends, 'frames', 'startup', 'min'),
                    static::getData($powerCrush, $extends, 'frames', 'startup', 'max')
                ),
                new Absorption(
                    static::getData($powerCrush, $extends, 'frames', 'absorption', 'min'),
                    static::getData($powerCrush, $extends, 'frames', 'absorption', 'max')
                ),
                new AfterAbsorption(static::getData($powerCrush, $extends, 'frames', 'after-absorption', 'block')),
                new Block($powerCrush['frames']['block']['min'], $powerCrush['frames']['block']['max']),
                $powerCrush['frames']['normal-hit'],
                $powerCrush['frames']['counter-hit']
            ),
            new Damages(
                static::getData($powerCrush, $extends, 'damages', 'normal-hit'),
                static::getData($powerCrush, $extends, 'damages', 'counter-hit')
            ),
            new Behaviors(
                BehaviorsFactory::create(static::getData($powerCrush, $extends, 'behaviors', 'block')),
                BehaviorsFactory::create(static::getData($powerCrush, $extends, 'behaviors', 'normal-hit')),
                BehaviorsFactory::create(static::getData($powerCrush, $extends, 'behaviors', 'counter-hit'))
            ),
            new Steps(
                is_string(static::getData($powerCrush, $extends, 'steps', 'ssl'))
                    ? StepEnum::create(static::getData($powerCrush, $extends, 'steps', 'ssl'))
                    : null,
                is_string(static::getData($powerCrush, $extends, 'steps', 'swl'))
                    ? StepEnum::create(static::getData($powerCrush, $extends, 'steps', 'swl'))
                    : null,
                is_string(static::getData($powerCrush, $extends, 'steps', 'ssr'))
                    ? StepEnum::create(static::getData($powerCrush, $extends, 'steps', 'ssr'))
                    : null,
                is_string(static::getData($powerCrush, $extends, 'steps', 'swr'))
                    ? StepEnum::create(static::getData($powerCrush, $extends, 'steps', 'swr'))
                    : null,
            ),
            CommentsFactory::create($powerCrush['comments'])
        );
    }

    private static function getData(array &$powerCrush, ?array &$extends, string ...$keys): mixed
    {
        $powerCrushDepth = $powerCrush;
        foreach ($keys as $key) {
            $powerCrushDepth = $powerCrushDepth[$key] ?? null;
        }

        $extendsDepth = $extends;
        if (is_null($powerCrushDepth) && is_array($extends)) {
            foreach ($keys as $key) {
                $extendsDepth = $extendsDepth[$key] ?? null;
            }
        }

        return $powerCrushDepth ?? $extendsDepth;
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
            throw new AppException('Move "' . $id . '" not found.');
        }

        return $return;
    }
}
