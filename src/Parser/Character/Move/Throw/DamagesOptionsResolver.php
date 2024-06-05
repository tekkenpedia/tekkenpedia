<?php

declare(strict_types=1);

namespace App\Parser\Character\Move\Throw;

use App\OptionsResolver\AllowedTypeEnum;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\{
    Constraints\Positive,
    Validation
};

class DamagesOptionsResolver
{
    public static function configure(OptionsResolver $resolver): void
    {
        $resolver
            ->define('normal')
            ->required()
            ->allowedTypes(AllowedTypeEnum::INTEGER->value)
            ->allowedValues(Validation::createIsValidCallable(new Positive()));

        $resolver
            ->define('wall')
            ->default(null)
            ->allowedTypes(AllowedTypeEnum::NULL->value, AllowedTypeEnum::INTEGER->value)
            ->allowedValues(Validation::createIsValidCallable(new Positive()));

        $resolver
            ->define('ukemi')
            ->default(null)
            ->allowedTypes(AllowedTypeEnum::NULL->value, AllowedTypeEnum::INTEGER->value)
            ->allowedValues(Validation::createIsValidCallable(new Positive()));
    }
}
