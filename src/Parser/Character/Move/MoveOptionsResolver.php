<?php

declare(strict_types=1);

namespace App\Parser\Character\Move;

use App\{
    Exception\AppException,
    OptionsResolver\AllowedTypeEnum,
    Parser\Character\Move\Attack\RootOptionsResolver as AttackRootOptionsResolver,
    Parser\Character\Move\PowerCrush\RootOptionsResolver as PowerCrushRootOptionsResolver,
    Parser\Character\Move\Throw\RootOptionsResolver as ThrowRootOptionsResolver
};
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoveOptionsResolver
{
    /** @param array{type: string|null} $data */
    public static function configure(OptionsResolver $resolver, string $name, array &$data): void
    {
        $type = MoveTypeEnum::create($data['type'] ?? MoveTypeEnum::ATTACK->name);

        if ($type === MoveTypeEnum::ATTACK) {
            static::configureAttack($resolver, $name);
        } elseif ($type === MoveTypeEnum::POWER_CRUSH) {
                static::configurePowerCrush($resolver, $name);
        } elseif ($type === MoveTypeEnum::THROW) {
            static::configureThrow($resolver, $name);
        } else {
            throw new AppException('Attack type "' . $type->name . '" is not taken into account.');
        }
    }

    private static function configureAttack(OptionsResolver $resolver, string $name): void
    {
        $resolver
            ->define($name)
            ->default(
                static function (OptionsResolver $moveResolver): void {
                    AttackRootOptionsResolver::configure($moveResolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);
    }

    private static function configurePowerCrush(OptionsResolver $resolver, string $name): void
    {
        $resolver
            ->define($name)
            ->default(
                static function (OptionsResolver $moveResolver): void {
                    PowerCrushRootOptionsResolver::configure($moveResolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);
    }

    private static function configureThrow(OptionsResolver $resolver, string $name): void
    {
        $resolver
            ->define($name)
            ->default(
                static function (OptionsResolver $moveResolver): void {
                    ThrowRootOptionsResolver::configure($moveResolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);
    }
}
