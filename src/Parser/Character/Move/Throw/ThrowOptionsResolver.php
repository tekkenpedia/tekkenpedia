<?php

declare(strict_types=1);

namespace App\Parser\Character\Move\Throw;

use App\{
    Character\Move\Behavior\BehaviorEnum,
    Character\Move\Throw\PropertyEnum,
    OptionsResolver\AllowedTypeEnum,
    Parser\Character\CommentOptionsResolver,
    Parser\Character\Move\MoveTypeEnum,
    Parser\Character\Move\Throw\Frames\FramesOptionsResolver
};
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThrowOptionsResolver
{
    public static function configure(OptionsResolver $resolver): void
    {
        $resolver
            ->define('type')
            ->required()
            ->allowedTypes(AllowedTypeEnum::STRING->value)
            ->allowedValues(MoveTypeEnum::THROW->name);

        $resolver
            ->define('slug')
            ->required()
            ->allowedTypes(AllowedTypeEnum::STRING->value);

        $resolver
            ->define('frames')
            ->required()
            ->default(
                static function(OptionsResolver $framesResolver): void {
                    FramesOptionsResolver::configure($framesResolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $resolver
            ->define('distances')
            ->required()
            ->default(
                static function(OptionsResolver $distancesResolver): void {
                    DistancesOptionsResolver::configure($distancesResolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $resolver
            ->define('escapes')
            ->default([])
            ->allowedTypes(AllowedTypeEnum::ARRAY_OF_STRINGS->value);

        $resolver
            ->define('damages')
            ->required()
            ->default(
                static function(OptionsResolver $damagesResolver): void {
                    DamagesOptionsResolver::configure($damagesResolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $resolver
            ->define('property')
            ->default(PropertyEnum::HIGH->name)
            ->allowedValues(...PropertyEnum::getNames()->toArray());

        $resolver
            ->define('behaviors')
            ->default([])
            ->allowedValues(
                static function(array $behaviors): bool {
                    $allowedBehaviors = BehaviorEnum::getNames();
                    foreach ($behaviors as $behavior) {
                        if (in_array($behavior, $allowedBehaviors->toArray(), true) === false) {
                            return false;
                        }
                    }

                    return true;
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY_OF_STRINGS->value);

        $resolver
            ->define('comments')
            ->default([])
            ->allowedValues(
                static function(array &$comments): bool {
                    return CommentOptionsResolver::resolve($comments);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);
    }
}
