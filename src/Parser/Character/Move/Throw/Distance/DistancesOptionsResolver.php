<?php

declare(strict_types=1);

namespace App\Parser\Character\Move\Throw\Distance;

use App\OptionsResolver\AllowedTypeEnum;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\{
    Constraints\Positive,
    Validation
};

class DistancesOptionsResolver
{
    public static function configure(OptionsResolver $resolver): void
    {
        $resolver
            ->define('range')
            ->required()
            ->allowedTypes(AllowedTypeEnum::INTEGER->value)
            ->allowedValues(Validation::createIsValidCallable(new Positive()));

        $resolver
            ->define('hit')
            ->required()
            ->default(
                static function (OptionsResolver $hitResolver): void {
                    HitOptionsResolver::configure($hitResolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $resolver
            ->define('escape')
            ->default(null)
            ->allowedTypes(AllowedTypeEnum::NULL->value, AllowedTypeEnum::INTEGER->value)
            ->allowedValues(Validation::createIsValidCallable(new Positive()));
    }
}
