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

        $resolver
            ->define('comments')
            ->default([])
            ->allowedValues(
                /** @param array<mixed> $comments */
                static function (array &$comments): bool {
                    return CommentOptionsResolver::resolve($comments);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        SectionsOptionsResolver::configure($resolver, $data);
    }
}
