<?php

declare(strict_types=1);

namespace App\Parser\Character;

use App\OptionsResolver\AllowedTypeEnum;
use App\Parser\Character\Move\MovesOptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CharacterOptionsResolver
{
    public static function configure(OptionsResolver $resolver, array &$data): void
    {
        $resolver
            ->define('name')
            ->required()
            ->allowedTypes(AllowedTypeEnum::STRING->value);

        $resolver
            ->define('slug')
            ->required()
            ->allowedTypes(AllowedTypeEnum::STRING->value);

        MovesOptionsResolver::configure($resolver, $data);
    }
}
