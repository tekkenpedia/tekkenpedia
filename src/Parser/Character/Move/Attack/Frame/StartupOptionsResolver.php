<?php

declare(strict_types=1);

namespace App\Parser\Character\Move\Attack\Frame;

use App\OptionsResolver\AllowedTypeEnum;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\{
    Constraints\Positive,
    Validation
};

class StartupOptionsResolver
{
    public static function configure(OptionsResolver $resolver): void
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
