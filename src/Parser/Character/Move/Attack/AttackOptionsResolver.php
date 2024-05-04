<?php

declare(strict_types=1);

namespace App\Parser\Character\Move\Attack;

use App\{
    Character\Move\Attack\PropertyEnum,
    Character\Move\Behavior\BehaviorEnum,
    OptionsResolver\AllowedTypeEnum,
    Parser\Character\CommentOptionsResolver,
    Parser\Character\Move\Attack\Frame\FramesOptionsResolver,
    Parser\Character\Move\MoveTypeEnum,
    Parser\Character\Move\VisibilityOptionsResolver
};
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttackOptionsResolver
{
    public static function configure(OptionsResolver $resolver): void
    {
        $resolver
            ->define('type')
            ->default(null)
            ->allowedTypes(AllowedTypeEnum::STRING->value, AllowedTypeEnum::NULL->value)
            ->allowedValues(MoveTypeEnum::ATTACK->name, null);

        $resolver
            ->define('name')
            ->required()
            ->allowedTypes(AllowedTypeEnum::STRING->value);

        $resolver
            ->define('parent')
            ->default(null)
            ->allowedTypes(AllowedTypeEnum::STRING->value, AllowedTypeEnum::NULL->value);

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
            ->default(['normal-hit' => [], 'counter-hit' => []])
            ->allowedValues(
                static function(array $behaviors): bool {
                    $allowedBehaviors = BehaviorEnum::getNames();
                    foreach (array_merge($behaviors['normal-hit'], $behaviors['counter-hit']) as $behavior) {
                        if (in_array($behavior, $allowedBehaviors->toArray(), true) === false) {
                            return false;
                        }
                    }

                    return true;
                }
            );
    }
}
