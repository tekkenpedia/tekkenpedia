<?php

declare(strict_types=1);

namespace App\Parser\Character\Move;

use App\{
    Parser\Character\Move\Attack\RootOptionsResolver as AttackRootOptionsResolver,
    Parser\Character\Move\PowerCrush\RootOptionsResolver as PowerCrushRootOptionsResolver,
    Parser\Character\Move\Throw\RootOptionsResolver as ThrowRootOptionsResolver
};

class MoveOptionsResolverFactory
{
    /** @param array<mixed> $data */
    public static function create(
        array &$data
    ): AttackRootOptionsResolver|PowerCrushRootOptionsResolver|ThrowRootOptionsResolver {
        $type = MoveTypeEnum::create($data['type'] ?? MoveTypeEnum::ATTACK->name);

        return match ($type) {
            MoveTypeEnum::ATTACK => new AttackRootOptionsResolver(),
            MoveTypeEnum::POWER_CRUSH => new PowerCrushRootOptionsResolver(),
            MoveTypeEnum::THROW => new ThrowRootOptionsResolver()
        };
    }
}
