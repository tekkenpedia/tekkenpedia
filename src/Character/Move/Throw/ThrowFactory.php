<?php

declare(strict_types=1);

namespace App\Character\Move\Throw;

use App\{
    Character\Move\Behavior\BehaviorsFactory,
    Character\Move\Comment\CommentsFactory,
    Character\Move\Throw\Distance\Distances,
    Character\Move\Throw\Distance\Hit as DistancesHit,
    Character\Move\Throw\Frame\Escape,
    Character\Move\Throw\Frame\Frames,
    Character\Move\Throw\Frame\Hit\Hit as FramesHit,
    Character\Move\Throw\Frame\Hit\Wall,
    Character\Move\Throw\Frame\Startup,
    Character\Move\Visibility
};
use Steevanb\PhpCollection\ScalarCollection\StringCollection;

class ThrowFactory
{
    /** @param TThrow $throw */
    public static function create(string $id, array &$throw): Throw_
    {
        return new Throw_(
            $id,
            $throw['inputs'],
            $throw['situation'],
            $throw['slug'] ?? $throw['inputs'],
            new Visibility($throw['visibility']['defense']),
            PropertyEnum::create($throw['property']),
            new Frames(
                new Startup($throw['frames']['startup']['min'], $throw['frames']['startup']['max']),
                new FramesHit(
                    $throw['frames']['hit']['normal'],
                    new Wall(
                        $throw['frames']['hit']['wall']['normal'],
                        $throw['frames']['hit']['wall']['splat'],
                        $throw['frames']['hit']['wall']['break']
                    ),
                    $throw['frames']['hit']['ukemi'],
                ),
                new Escape(
                    $throw['frames']['escape']['normal-hit'],
                    $throw['frames']['escape']['counter-hit']
                ),
                $throw['frames']['after-escape']
            ),
            new Distances(
                $throw['distances']['range'],
                new DistancesHit($throw['distances']['hit']['normal'], $throw['distances']['hit']['ukemi']),
                $throw['distances']['escape']
            ),
            new StringCollection($throw['escapes']),
            new Damages($throw['damages']['normal'], $throw['damages']['ukemi'], $throw['damages']['wall']),
            BehaviorsFactory::create($throw['behaviors']),
            CommentsFactory::create($throw['comments'])
        );
    }
}
