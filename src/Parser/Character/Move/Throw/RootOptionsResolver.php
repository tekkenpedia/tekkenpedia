<?php

declare(strict_types=1);

namespace App\Parser\Character\Move\Throw;

use App\{
    Character\Move\Behavior\BehaviorEnum,
    Character\Move\Throw\PropertyEnum,
    OptionsResolver\AllowedTypeEnum,
    Parser\Character\CommentOptionsResolver,
    Parser\Character\Move\MoveTypeEnum,
    Parser\Character\Move\Throw\Distance\DistancesOptionsResolver,
    Parser\Character\Move\Throw\Frame\FramesOptionsResolver,
    Parser\Character\Move\VisibilityOptionsResolver
};
use Symfony\Component\OptionsResolver\OptionsResolver;

class RootOptionsResolver extends OptionsResolver
{
    public function __construct()
    {
        $this
            ->define('type')
            ->default(MoveTypeEnum::THROW->name)
            ->allowedTypes(AllowedTypeEnum::STRING->value)
            ->allowedValues(MoveTypeEnum::THROW->name);

        $this
            ->define('inputs')
            ->required()
            ->allowedTypes(AllowedTypeEnum::STRING->value);

        $this
            ->define('situation')
            ->default(null)
            ->allowedTypes(AllowedTypeEnum::STRING->value, AllowedTypeEnum::NULL->value);

        $this
            ->define('slug')
            ->default(null)
            ->allowedTypes(AllowedTypeEnum::STRING->value, AllowedTypeEnum::NULL->value);

        $this
            ->define('visibility')
            ->default(
                static function (OptionsResolver $visibilityResolver): void {
                    VisibilityOptionsResolver::configure($visibilityResolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $this
            ->define('frames')
            ->required()
            ->default(
                static function (OptionsResolver $framesResolver): void {
                    FramesOptionsResolver::configure($framesResolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $this
            ->define('distances')
            ->required()
            ->default(
                static function (OptionsResolver $distancesResolver): void {
                    DistancesOptionsResolver::configure($distancesResolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $this
            ->define('escapes')
            ->default([])
            ->allowedTypes(AllowedTypeEnum::ARRAY_OF_STRINGS->value);

        $this
            ->define('damages')
            ->required()
            ->default(
                static function (OptionsResolver $damagesResolver): void {
                    DamagesOptionsResolver::configure($damagesResolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $this
            ->define('property')
            ->default(PropertyEnum::HIGH->name)
            ->allowedValues(...PropertyEnum::getNames()->toArray());

        $this
            ->define('behaviors')
            ->default([])
            ->allowedValues(
                static function (array $behaviors): bool {
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

        $this
            ->define('comments')
            ->default([])
            ->allowedValues(
                /** @param array<mixed> $comments */
                static function (array &$comments): bool {
                    return CommentOptionsResolver::resolve($comments);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);
    }

    /**
     * @param array<mixed> $options
     * @return TThrow
     */
    public function resolve(array $options = []): array
    {
        /** @var TThrow $return */
        $return = parent::resolve($options);

        return $return;
    }
}
