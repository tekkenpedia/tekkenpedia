<?php

declare(strict_types=1);

namespace App\Parser\Character\Move\Throw\Frame\Hit;

use App\OptionsResolver\AllowedTypeEnum;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HitOptionsResolver
{
    public static function configure(OptionsResolver $resolver): void
    {
        $resolver
            ->define('normal')
            ->required()
            ->allowedTypes(AllowedTypeEnum::INTEGER->value);

        $resolver
            ->define('wall')
            ->default(
                static function (OptionsResolver $resolver): void {
                    WallOptionsResolver::configure($resolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $resolver
            ->define('ukemi')
            ->default(null)
            ->allowedTypes(AllowedTypeEnum::INTEGER->value, AllowedTypeEnum::NULL->value);
    }
}
