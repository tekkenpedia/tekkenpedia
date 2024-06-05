<?php

declare(strict_types=1);

namespace App\Character\Move\Attack;

use App\{
    Character\Move\Attack\Frame\Block,
    Character\Move\Attack\Frame\Frames,
    Character\Move\Attack\Frame\Startup,
    Character\Move\Behavior\BehaviorsFactory,
    Character\Move\Comment\CommentsFactory,
    Character\Move\Damages as DamagesData,
    Character\Move\Distance\MinMax,
    Character\Move\Step\StepEnum,
    Character\Move\Step\Steps,
    Character\Move\Visibility
};

class AttackFactory
{
    /** @param TAttack $attack */
    public static function create(string $id, array &$attack): Attack
    {
        $slug = $attack['slug'] ?? $attack['inputs'];
        if ($attack['heat']) {
            $slug .= '_heat-activated';
        }

        return new Attack(
            $attack['master'],
            $id,
            $attack['inputs'],
            $attack['situation'],
            $slug,
            $attack['heat'],
            new Visibility($attack['visibility']['defense']),
            PropertyEnum::create($attack['property']),
            static::createDistances($attack),
            static::createFrames($attack),
            static::createDamages($attack),
            static::createBehaviors($attack),
            static::createSteps($attack),
            CommentsFactory::create($attack['comments'])
        );
    }

    /** @param TAttack $attack */
    private static function createDistances(array &$attack): Distances
    {
        return new Distances(
            $attack['distances']['range'],
            new MinMax(
                $attack['distances']['block']['min'],
                $attack['distances']['block']['max']
            ),
            new MinMax(
                $attack['distances']['normal-hit']['min'],
                $attack['distances']['normal-hit']['max']
            ),
            new MinMax(
                $attack['distances']['counter-hit']['min'],
                $attack['distances']['counter-hit']['max']
            )
        );
    }

    /** @param TAttack $attack */
    private static function createFrames(array &$attack): Frames
    {
        return new Frames(
            new Startup(
                $attack['frames']['startup']['min'],
                $attack['frames']['startup']['max']
            ),
            new Block($attack['frames']['block']['min'], $attack['frames']['block']['max']),
            $attack['frames']['normal-hit'],
            $attack['frames']['counter-hit']
        );
    }

    /** @param TAttack $attack */
    private static function createDamages(array &$attack): Damages
    {
        return new Damages(
            new DamagesData(
                $attack['damages']['block']['damage'] ?? null,
                $attack['damages']['block']['recoverable-damage'] ?? null
            ),
            new DamagesData(
                $attack['damages']['normal-hit']['damage'] ?? null,
                $attack['damages']['normal-hit']['recoverable-damage'] ?? null
            ),
            new DamagesData(
                $attack['damages']['counter-hit']['damage'] ?? null,
                $attack['damages']['counter-hit']['recoverable-damage'] ?? null
            ),
        );
    }

    /** @param TAttack $attack */
    private static function createBehaviors(array &$attack): Behaviors
    {
        return new Behaviors(
            BehaviorsFactory::create($attack['behaviors']['block']),
            BehaviorsFactory::create($attack['behaviors']['normal-hit']),
            BehaviorsFactory::create($attack['behaviors']['counter-hit'])
        );
    }

    /** @param TAttack $attack */
    private static function createSteps(array &$attack): Steps
    {
        $ssl = $attack['steps']['ssl'];
        $swl = $attack['steps']['swl'];
        $ssr = $attack['steps']['ssr'];
        $swr = $attack['steps']['swr'];

        return new Steps(
            is_string($ssl) ? StepEnum::create($ssl) : null,
            is_string($swl) ? StepEnum::create($swl) : null,
            is_string($ssr) ? StepEnum::create($ssr) : null,
            is_string($swr) ? StepEnum::create($swr) : null
        );
    }
}
