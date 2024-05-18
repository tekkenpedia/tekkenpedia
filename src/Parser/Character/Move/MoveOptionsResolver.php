<?php

declare(strict_types=1);

namespace App\Parser\Character\Move;

use App\{
    Exception\AppException,
    OptionsResolver\AllowedTypeEnum,
    Parser\Character\Move\Attack\AttackOptionsResolver,
    Parser\Character\Move\Throw\ThrowOptionsResolver
};
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoveOptionsResolver
{
    public static function configure(OptionsResolver $resolver, string $name, array &$moveData): void
    {
        $type = MoveTypeEnum::create($moveData['type'] ?? MoveTypeEnum::ATTACK->name);

        if ($type === MoveTypeEnum::ATTACK) {
            static::configureAttack($resolver, $name, $moveData);
        } elseif ($type === MoveTypeEnum::THROW) {
            static::configureThrow($resolver, $name, $moveData);
        } else {
            throw new AppException('Attack type "' . $type->name . '" is not taken into account.');
        }
    }

    private static function configureAttack(OptionsResolver $resolver, string $name, array &$data): void
    {
        $resolver
            ->define($name)
            ->default(
                static function(OptionsResolver $moveResolver) use (&$data): void {
                    AttackOptionsResolver::configure($moveResolver, $data);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);
    }

    private static function configureThrow(OptionsResolver $resolver, string $name, array &$throwData): void
    {
        $resolver
            ->define($name)
            ->default(
                static function(OptionsResolver $moveResolver) use (&$throwData): void {
                    ThrowOptionsResolver::configure($moveResolver, $throwData);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);
    }
}
