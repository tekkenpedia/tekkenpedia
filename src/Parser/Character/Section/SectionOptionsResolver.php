<?php

declare(strict_types=1);

namespace App\Parser\Character\Section;

use App\OptionsResolver\AllowedTypeEnum;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SectionOptionsResolver
{
    /** @param array<mixed> $data */
    public static function configure(OptionsResolver $resolver, array &$data): void
    {
        $resolver
            ->define('moves')
            ->default([])
            ->allowedTypes(AllowedTypeEnum::ARRAY_OF_STRINGS->value);

        SectionsOptionsResolver::configure($resolver, $data);
    }
}
