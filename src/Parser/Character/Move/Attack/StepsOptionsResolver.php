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
            ->default(null)
            ->allowedValues(...[...StepEnum::getNames()->toArray(), null]);

        $resolver
            ->define('swl')
            ->default(null)
            ->allowedValues(...[...StepEnum::getNames()->toArray(), null]);

        $resolver
            ->define('ssr')
            ->default(null)
            ->allowedValues(...[...StepEnum::getNames()->toArray(), null]);

        $resolver
            ->define('swr')
            ->default(null)
            ->allowedValues(...[...StepEnum::getNames()->toArray(), null]);
    }
}
