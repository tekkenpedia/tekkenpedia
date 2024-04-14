<?php

declare(strict_types=1);

namespace App\Character\Move\Throw;

use App\{
    Character\Move\Behavior\BehaviorsFactory,
    Character\Move\Comment\CommentsFactory,
    Character\Move\Throw\Distance\Distances,
    Character\Move\Throw\Distance\Hit as DistanceHit,
    Character\Move\Throw\Frame\Frames,
    Character\Move\Throw\Frame\Hit as FrameHit,
    Character\Move\Throw\Frame\Startup
};
use Steevanb\PhpCollection\ScalarCollection\StringCollection;

class ThrowFactory
{
    public static function create(string $name, array &$throw): Throw_
    {
        return new Throw_(
            $name,
            $throw['slug'],
            PropertyEnum::create($throw['property']),
            new Frames(
                new Startup($throw['frames']['startup']['min'], $throw['frames']['startup']['max']),
                new FrameHit(
                    $throw['frames']['hit']['normal'],
                    $throw['frames']['hit']['ukemi'],
                    $throw['frames']['hit']['wall']
                ),
                $throw['frames']['escape'],
                $throw['frames']['after-escape']
            ),
            new Distances(
                $throw['distances']['startup'],
                new DistanceHit($throw['distances']['hit']['normal'], $throw['distances']['hit']['ukemi']),
                $throw['distances']['escape']
            ),
            new StringCollection($throw['escapes']),
            new Damages($throw['damages']['normal'], $throw['damages']['ukemi'], $throw['damages']['wall']),
            BehaviorsFactory::create($throw['behaviors']),
            CommentsFactory::create($throw['comments'])
        );
    }
}
