<?php

declare(strict_types=1);

namespace App\Parser\Character\Move\PowerCrush\Frame;

use App\{
    OptionsResolver\AllowedTypeEnum,
    Parser\Character\Move\MinMaxFramesOptionsResolver
};
use Symfony\Component\OptionsResolver\OptionsResolver;

class FramesOptionsResolver
{
    public static function configure(OptionsResolver $resolver): void
    {
        $resolver
            ->define('startup')
            ->default(
                static function(OptionsResolver $resolver): void {
                    MinMaxFramesOptionsResolver::configure($resolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $resolver
            ->define('absorption')
            ->default(
                static function(OptionsResolver $resolver): void {
                    MinMaxFramesOptionsResolver::configure($resolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $resolver
            ->define('after-absorption')
            ->default(
                static function(OptionsResolver $resolver): void {
                    AfterAbsorptionOptionsResolver::configure($resolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $resolver
            ->define('block')
            ->default(
                static function(OptionsResolver $resolver): void {
                    MinMaxFramesOptionsResolver::configure($resolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $resolver
            ->define('normal-hit')
            ->required()
            ->allowedTypes(AllowedTypeEnum::INTEGER->value);

        $resolver
            ->define('counter-hit')
            ->required()
            ->allowedTypes(AllowedTypeEnum::INTEGER->value);
    }
}
