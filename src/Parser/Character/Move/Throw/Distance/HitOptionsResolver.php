<?php

declare(strict_types=1);

namespace App\Parser\Character\Move\Throw\Distance;

use App\OptionsResolver\AllowedTypeEnum;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\{
    Constraints\Positive,
    Validation
};

class HitOptionsResolver
{
    public static function configure(OptionsResolver $resolver): void
    {
        $resolver
            ->define('normal')
            ->required()
            ->allowedTypes(AllowedTypeEnum::INTEGER->value)
            ->allowedValues(Validation::createIsValidCallable(new Positive()));

        $resolver
            ->define('ukemi')
            ->default(null)
            ->allowedTypes(AllowedTypeEnum::INTEGER->value, AllowedTypeEnum::NULL->value)
            ->allowedValues(Validation::createIsValidCallable(new Positive()));
    }
}
