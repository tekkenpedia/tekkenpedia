<?php

declare(strict_types=1);

namespace App\Parser\Character;

use App\OptionsResolver\AllowedTypeEnum;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MinMaxNullableOptionsResolver
{
    public static function configure(OptionsResolver $resolver): void
    {
        $resolver
            ->define('min')
            ->default(null)
            ->allowedTypes(AllowedTypeEnum::INTEGER->value, AllowedTypeEnum::NULL->value);

        $resolver
            ->define('max')
            ->default(null)
            ->allowedTypes(AllowedTypeEnum::INTEGER->value, AllowedTypeEnum::NULL->value);
    }
}
