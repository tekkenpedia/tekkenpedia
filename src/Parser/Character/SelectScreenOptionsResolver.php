<?php

declare(strict_types=1);

namespace App\Parser\Character;

use App\{
    OptionsResolver\AllowedTypeEnum,
    Parser\Character\Move\MovesOptionsResolver
};
use Symfony\Component\OptionsResolver\OptionsResolver;

class SelectScreenOptionsResolver
{
    public static function configure(OptionsResolver $resolver): void
    {
        $resolver
            ->define('line')
            ->required()
            ->allowedTypes(AllowedTypeEnum::INTEGER->value);

        $resolver
            ->define('position')
            ->required()
            ->allowedTypes(AllowedTypeEnum::INTEGER->value);
    }
}
