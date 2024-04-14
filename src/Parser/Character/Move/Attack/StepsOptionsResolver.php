<?php

declare(strict_types=1);

namespace App\Parser\Character\Move\Attack;

use App\Character\Move\Step\StepEnum;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StepsOptionsResolver
{
    public static function configure(OptionsResolver $resolver): void
    {
        $resolver
            ->define('ssl')
            ->default(StepEnum::IMPOSSIBLE->name)
            ->allowedValues(...StepEnum::getNames()->toArray());

        $resolver
            ->define('swl')
            ->default(StepEnum::IMPOSSIBLE->name)
            ->allowedValues(...StepEnum::getNames()->toArray());

        $resolver
            ->define('ssr')
            ->default(StepEnum::IMPOSSIBLE->name)
            ->allowedValues(...StepEnum::getNames()->toArray());

        $resolver
            ->define('swr')
            ->default(StepEnum::IMPOSSIBLE->name)
            ->allowedValues(...StepEnum::getNames()->toArray());
    }
}
