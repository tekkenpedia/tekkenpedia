<?php

declare(strict_types=1);

namespace App\Parser\Character\Move;

use App\{
    Exception\AppException,
    OptionsResolver\AllowedTypeEnum,
    Parser\Character\Move\Attack\AttackOptionsResolver,
    Parser\Character\Move\Throw\ThrowOptionsResolver};
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoveOptionsResolver
{
    public static function configure(OptionsResolver $resolver, string $name, array &$data): void
    {
        $type = MoveTypeEnum::create($data['type'] ?? 'ATTACK');

        if ($type === MoveTypeEnum::ATTACK) {
            static::configureAttack($resolver, $name);
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
                static function(OptionsResolver $moveResolver): void {
                    AttackOptionsResolver::configure($moveResolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);
    }

    private static function configureThrow(OptionsResolver $resolver, string $name): void
    {
        $resolver
            ->define($name)
            ->default(
                static function(OptionsResolver $moveResolver): void {
                    ThrowOptionsResolver::configure($moveResolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);
    }
}
