<?php

declare(strict_types=1);

namespace App\Parser\Character\Move\PowerCrush;

use App\{
    OptionsResolver\AllowedTypeEnum,
    Parser\Character\Move\DamagesOptionsResolver as DamagesDataOptionsResolver
};
use Symfony\Component\OptionsResolver\OptionsResolver;

class DamagesOptionsResolver
{
    public static function configure(OptionsResolver $resolver): void
    {
        $resolver
            ->define('block')
            ->default(
                static function (OptionsResolver $resolver): void {
                    DamagesDataOptionsResolver::configure($resolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $resolver
            ->define('normal-hit')
            ->default(
                static function (OptionsResolver $resolver): void {
                    DamagesDataOptionsResolver::configure($resolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $resolver
            ->define('counter-hit')
            ->default(
                static function (OptionsResolver $resolver): void {
                    DamagesDataOptionsResolver::configure($resolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);
    }
}
