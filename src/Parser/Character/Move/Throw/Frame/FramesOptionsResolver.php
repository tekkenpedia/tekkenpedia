<?php

declare(strict_types=1);

namespace App\Parser\Character\Move\Throw\Frame;

use App\{
    OptionsResolver\AllowedTypeEnum,
    Parser\Character\MinMaxOptionsResolver,
    Parser\Character\Move\Throw\Frame\Hit\HitOptionsResolver
};
use Symfony\Component\OptionsResolver\OptionsResolver;

class FramesOptionsResolver
{
    public static function configure(OptionsResolver $resolver): void
    {
        $resolver
            ->define('startup')
            ->default(
                static function (OptionsResolver $resolver): void {
                    MinMaxOptionsResolver::configure($resolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $resolver
            ->define('hit')
            ->default(
                static function (OptionsResolver $resolver): void {
                    HitOptionsResolver::configure($resolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $resolver
            ->define('escape')
            ->default(
                static function (OptionsResolver $resolver): void {
                    EscapeOptionsResolver::configure($resolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $resolver
            ->define('after-escape')
            ->default(null)
            ->allowedTypes(AllowedTypeEnum::NULL->value, AllowedTypeEnum::INTEGER->value);
    }
}
