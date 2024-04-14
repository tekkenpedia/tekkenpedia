<?php

declare(strict_types=1);

namespace App\Parser\Character\Move\Attack;

use App\{
    Character\Move\Attack\PropertyEnum,
    Character\Move\Behavior\BehaviorEnum,
    OptionsResolver\AllowedTypeEnum,
    Parser\Character\CommentOptionsResolver,
    Parser\Character\Move\MoveTypeEnum};
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttackOptionsResolver
{
    public static function configure(OptionsResolver $resolver): void
    {
        $resolver
            ->define('type')
            ->default(MoveTypeEnum::ATTACK->name)
            ->allowedTypes(AllowedTypeEnum::STRING->value)
            ->allowedValues(MoveTypeEnum::ATTACK->name);

        $resolver
            ->define('slug')
            ->required()
            ->allowedTypes(AllowedTypeEnum::STRING->value);

        $resolver
            ->define('property')
            ->required()
            ->allowedValues(...PropertyEnum::getNames()->toArray());

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
