<?php

declare(strict_types=1);

namespace App\Character\Move\Attack;

use App\{
    Character\Move\Behavior\BehaviorsFactory,
    Character\Move\Comment\CommentsFactory,
    Character\Move\Distance\MinMax,
    Character\Move\Step\StepEnum,
    Character\Move\Step\Steps
};

class AttackFactory
{
    public static function create(string $name, array &$attack): Attack
    {
        return new Attack(
            $name,
            $attack['slug'],
            PropertyEnum::create($attack['property']),
            new Distances(
                new MinMax($attack['distances']['block']['min'], $attack['distances']['block']['max']),
                new MinMax($attack['distances']['normal-hit']['min'], $attack['distances']['normal-hit']['max']),
                new MinMax($attack['distances']['counter-hit']['min'], $attack['distances']['counter-hit']['max'])
            ),
            new Frames(
                $attack['frames']['startup'],
                $attack['frames']['normal-hit'],
                $attack['frames']['counter-hit'],
                $attack['frames']['block']
            ),
            new Damages($attack['damages']['normal-hit'], $attack['damages']['counter-hit']),
            BehaviorsFactory::create($attack['behaviors']['normal-hit']),
            BehaviorsFactory::create($attack['behaviors']['counter-hit']),
            new Steps(
                StepEnum::create($attack['steps']['ssl']),
                StepEnum::create($attack['steps']['swl']),
                StepEnum::create($attack['steps']['ssr']),
                StepEnum::create($attack['steps']['swr'])
            ),
            CommentsFactory::create($attack['comments'])
        );
    }
}
