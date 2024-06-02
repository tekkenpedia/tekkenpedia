<?php

declare(strict_types=1);

namespace App\Parser\Character\Move\Attack;

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
            ->default(null)
            ->allowedTypes(AllowedTypeEnum::INTEGER->value, AllowedTypeEnum::NULL->value)
            ->allowedValues(Validation::createIsValidCallable(new Positive()));

        static::defineMinMax($resolver, 'block');
        static::defineMinMax($resolver, 'normal-hit');
        static::defineMinMax($resolver, 'counter-hit');
    }

    private static function defineMinMax(OptionsResolver $resolver, string $option): void
    {
        $resolver
            ->define($option)
            ->default(
                static function(OptionsResolver $resolver): void {
                    static::configureMinMaxResolver($resolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);
    }

    private static function configureMinMaxResolver(OptionsResolver $resolver): void
    {
        $resolver
            ->define('min')
            ->default(null)
            ->allowedTypes(AllowedTypeEnum::INTEGER->value, AllowedTypeEnum::NULL->value)
            ->allowedValues(Validation::createIsValidCallable(new Positive()));

        $resolver
            ->define('max')
            ->default(null)
            ->allowedTypes(AllowedTypeEnum::INTEGER->value, AllowedTypeEnum::NULL->value)
            ->allowedValues(Validation::createIsValidCallable(new Positive()));
    }
}
