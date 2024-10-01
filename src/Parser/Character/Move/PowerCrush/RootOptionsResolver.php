<?php

declare(strict_types=1);

namespace App\Parser\Character\Move\PowerCrush;

use App\{
    Character\Move\PowerCrush\PropertyEnum,
    OptionsResolver\AllowedTypeEnum,
    Parser\Character\CommentOptionsResolver,
    Parser\Character\Move\MoveTypeEnum,
    Parser\Character\Move\PowerCrush\Frame\FramesOptionsResolver,
    Parser\Character\Move\VisibilityOptionsResolver
};
use Symfony\Component\OptionsResolver\OptionsResolver;

class RootOptionsResolver extends OptionsResolver
{
    public function __construct()
    {
        $this
            ->define('master')
            ->default(null)
            ->allowedTypes(AllowedTypeEnum::STRING->value, AllowedTypeEnum::NULL->value);

        $this
            ->define('type')
            ->default(MoveTypeEnum::POWER_CRUSH->name)
            ->allowedTypes(AllowedTypeEnum::STRING->value)
            ->allowedValues(MoveTypeEnum::POWER_CRUSH->name);

        $this
            ->define('inputs')
            ->required()
            ->allowedTypes(AllowedTypeEnum::STRING->value);

        $this
            ->define('situation')
            ->default(null)
            ->allowedTypes(AllowedTypeEnum::STRING->value, AllowedTypeEnum::NULL->value);

        $this
            ->define('heat')
            ->default(false)
            ->allowedTypes(AllowedTypeEnum::BOOLEAN->value);

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
            ->define('damage-reduction')
            ->default(null)
            ->allowedTypes(AllowedTypeEnum::INTEGER->value, AllowedTypeEnum::NULL->value);

        $this
            ->define('property')
            ->default(null)
            ->allowedTypes(AllowedTypeEnum::STRING->value, AllowedTypeEnum::NULL->value)
            ->allowedValues(...[...PropertyEnum::getNames()->toArray(), null]);

        $this
            ->define('frames')
            ->default(
                static function (OptionsResolver $framesResolver): void {
                    FramesOptionsResolver::configure($framesResolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $this
            ->define('distances')
            ->default(
                static function (OptionsResolver $damagesResolver): void {
                    DistancesOptionsResolver::configure($damagesResolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $this
            ->define('damages')
            ->default(
                static function (OptionsResolver $damagesResolver): void {
                    DamagesOptionsResolver::configure($damagesResolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $this
            ->define('steps')
            ->default(
                static function (OptionsResolver $stepsResolver): void {
                    StepsOptionsResolver::configure($stepsResolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

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

        $this
            ->define('behaviors')
            ->default(
                static function (OptionsResolver $behaviorsResolver): void {
                    BehaviorsOptionsResolver::configure($behaviorsResolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);
    }

    /**
     * @param array<mixed> $options
     * @return TPowerCrush
     */
    public function resolve(array $options = []): array
    {
        /** @var TPowerCrush $return */
        $return = parent::resolve($options);

        return $return;
    }
}
