<?php

declare(strict_types=1);

namespace App\Parser\Character\Move\PowerCrush;

use App\{
    Character\Move\PowerCrush\PropertyEnum,
    OptionsResolver\AllowedTypeEnum,
    Parser\Character\CommentOptionsResolver,
    Parser\Character\Move\PowerCrush\Frame\FramesOptionsResolver,
    Parser\Character\Move\MoveTypeEnum,
    Parser\Character\Move\VisibilityOptionsResolver
};
use Symfony\Component\OptionsResolver\OptionsResolver;

class RootOptionsResolver
{
    public static function configure(OptionsResolver $resolver): void
    {
        $resolver
            ->define('master')
            ->default(null)
            ->allowedTypes(AllowedTypeEnum::STRING->value, AllowedTypeEnum::NULL->value);

        $resolver
            ->define('type')
            ->default(null)
            ->allowedTypes(AllowedTypeEnum::STRING->value)
            ->allowedValues(MoveTypeEnum::POWER_CRUSH->name, null);

        $resolver
            ->define('inputs')
            ->required()
            ->allowedTypes(AllowedTypeEnum::STRING->value);

        $resolver
            ->define('situation')
            ->default(null)
            ->allowedTypes(AllowedTypeEnum::STRING->value, AllowedTypeEnum::NULL->value);

        $resolver
            ->define('heat')
            ->default(false)
            ->allowedTypes(AllowedTypeEnum::BOOLEAN->value);

        $resolver
            ->define('slug')
            ->default(null)
            ->allowedTypes(AllowedTypeEnum::STRING->value, AllowedTypeEnum::NULL->value);

        $resolver
            ->define('visibility')
            ->default(
                static function(OptionsResolver $visibilityResolver): void {
                    VisibilityOptionsResolver::configure($visibilityResolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $resolver
            ->define('damage-reduction')
            ->default(null)
            ->allowedTypes(AllowedTypeEnum::INTEGER->value, AllowedTypeEnum::NULL->value);

        $resolver
            ->define('property')
            ->default(null)
            ->allowedTypes(AllowedTypeEnum::STRING->value, AllowedTypeEnum::NULL->value)
            ->allowedValues(...[...PropertyEnum::getNames()->toArray(), null]);

        $resolver
            ->define('frames')
            ->default(
                static function(OptionsResolver $framesResolver): void {
                    FramesOptionsResolver::configure($framesResolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $resolver
            ->define('distances')
            ->default(
                static function(OptionsResolver $damagesResolver): void {
                    DistancesOptionsResolver::configure($damagesResolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $resolver
            ->define('damages')
            ->default(
                static function(OptionsResolver $damagesResolver): void {
                    DamagesOptionsResolver::configure($damagesResolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $resolver
            ->define('steps')
            ->default(
                static function(OptionsResolver $stepsResolver): void {
                    StepsOptionsResolver::configure($stepsResolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $resolver
            ->define('comments')
            ->default([])
            ->allowedValues(
                static function(array &$comments): bool {
                    return CommentOptionsResolver::resolve($comments);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $resolver
            ->define('behaviors')
            ->default(
                static function(OptionsResolver $behaviorsResolver): void {
                    BehaviorsOptionsResolver::configure($behaviorsResolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);
    }
}
