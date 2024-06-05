<?php

declare(strict_types=1);

namespace App\Parser\Character\Move;

use App\OptionsResolver\AllowedTypeEnum;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SectionOptionsResolver
{
    /** @param array<mixed> $data */
    public static function configure(OptionsResolver $resolver, array &$data): void
    {
        $resolver
            ->define('moves')
            ->default(
                static function (OptionsResolver $resolver) use (&$data): void {
                    foreach ($data['moves'] ?? [] as $name => $moveData) {
                        MoveOptionsResolver::configure($resolver, $name, $moveData);
                    }
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        SectionsOptionsResolver::configure($resolver, $data);
    }
}
