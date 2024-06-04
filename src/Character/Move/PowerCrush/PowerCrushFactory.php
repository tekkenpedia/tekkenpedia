<?php

declare(strict_types=1);

namespace App\Character\Move\PowerCrush;

use App\{
    Character\Move\Damages as DamagesData,
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
    Character\Move\Visibility
};

class PowerCrushFactory
{
    public static function create(string $id, array &$powerCrush): PowerCrush
    {
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
            PropertyEnum::create($powerCrush['property']),
            $powerCrush['damage-reduction'],
            static::createDistances($powerCrush),
            static::createFrames($powerCrush),
            static::createDamages($powerCrush),
            static::createBehaviors($powerCrush),
            static::createSteps($powerCrush),
            CommentsFactory::create($powerCrush['comments'])
        );
    }

    private static function createDistances(array &$powerCrush): Distances
    {
        return new Distances(
            $powerCrush['distances']['range'],
            new MinMax(
                $powerCrush['distances']['block']['min'],
                $powerCrush['distances']['block']['max']
            ),
            new MinMax(
                $powerCrush['distances']['normal-hit']['min'],
                $powerCrush['distances']['normal-hit']['max']
            ),
            new MinMax(
                $powerCrush['distances']['counter-hit']['min'],
                $powerCrush['distances']['counter-hit']['max']
            )
        );
    }

    private static function createFrames(array &$powerCrush): Frames
    {
        return new Frames(
            new Startup(
                $powerCrush['frames']['startup']['min'],
                $powerCrush['frames']['startup']['max']
            ),
            new Absorption(
                $powerCrush['frames']['absorption']['min'],
                $powerCrush['frames']['absorption']['max']
            ),
            new AfterAbsorption($powerCrush['frames']['after-absorption']['block']),
            new Block($powerCrush['frames']['block']['min'], $powerCrush['frames']['block']['max']),
            $powerCrush['frames']['normal-hit'],
            $powerCrush['frames']['counter-hit']
        );
    }

    private static function createDamages(array &$powerCrush): Damages
    {
        return new Damages(
            new DamagesData(
                $powerCrush['damages']['block']['damage'] ?? null,
                $powerCrush['damages']['block']['recoverable-damage'] ?? null
            ),
            new DamagesData(
                $powerCrush['damages']['normal-hit']['damage'] ?? null,
                $powerCrush['damages']['normal-hit']['recoverable-damage'] ?? null
            ),
            new DamagesData(
                $powerCrush['damages']['counter-hit']['damage'] ?? null,
                $powerCrush['damages']['counter-hit']['recoverable-damage'] ?? null
            ),
        );
    }

    private static function createBehaviors(array &$powerCrush): Behaviors
    {
        return new Behaviors(
            BehaviorsFactory::create($powerCrush['behaviors']['block']),
            BehaviorsFactory::create($powerCrush['behaviors']['normal-hit']),
            BehaviorsFactory::create($powerCrush['behaviors']['counter-hit'])
        );
    }

    private static function createSteps(array &$powerCrush): Steps
    {
        $ssl = $powerCrush['steps']['ssl'];
        $swl = $powerCrush['steps']['swl'];
        $ssr = $powerCrush['steps']['ssr'];
        $swr = $powerCrush['steps']['swr'];

        return new Steps(
            is_string($ssl) ? StepEnum::create($ssl) : null,
            is_string($swl) ? StepEnum::create($swl) : null,
            is_string($ssr) ? StepEnum::create($ssr) : null,
            is_string($swr) ? StepEnum::create($swr) : null
        );
    }
}
