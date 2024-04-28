<?php

declare(strict_types=1);

namespace App\Character\Move\Throw;

use App\{
    Character\Move\Behavior\BehaviorsFactory,
    Character\Move\Comment\CommentsFactory,
    Character\Move\Throw\Distance\Distances,
    Character\Move\Throw\Distance\Hit as DistancesHit,
    Character\Move\Throw\Frame\Frames,
    Character\Move\Throw\Frame\Hit as FramesHit,
    Character\Move\Throw\Frame\Startup
};
use Steevanb\PhpCollection\ScalarCollection\StringCollection;

class ThrowFactory
{
    public static function create(string $id, array &$throw): Throw_
    {
        return new Throw_(
            $id,
            $throw['name'],
            $throw['slug'] ?? $throw['name'],
            PropertyEnum::create($throw['property']),
            new Frames(
                new Startup($throw['frames']['startup']['min'], $throw['frames']['startup']['max']),
                new FramesHit(
                    $throw['frames']['hit']['normal'],
                    $throw['frames']['hit']['ukemi'],
                    $throw['frames']['hit']['wall']
                ),
                $throw['frames']['escape'],
                $throw['frames']['after-escape']
            ),
            new Distances(
                $throw['distances']['startup'],
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
