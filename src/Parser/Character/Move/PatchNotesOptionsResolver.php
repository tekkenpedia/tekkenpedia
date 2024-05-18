<?php

declare(strict_types=1);

namespace App\Parser\Character\Move;

use App\{
    Character\Move\PatchNote\PatchNoteCategoryEnum,
    Character\Move\PatchNote\PatchNoteTypeEnum,
    OptionsResolver\AllowedTypeEnum
};
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatchNotesOptionsResolver
{
    public static function configure(OptionsResolver $resolver, array &$moveData): void
    {
        $resolver
            ->define('patch-notes')
            ->default(
                function (OptionsResolver $resolver) use (&$moveData): void {
                    foreach (array_keys($moveData['patch-notes'] ?? []) as $patchNotesId) {
                        $resolver
                            ->define($patchNotesId)
                            ->default([])
                            ->allowedValues(
                                static function(array &$patchNotesData): bool {
                                    return PatchNotesOptionsResolver::resolve($patchNotesData);
                                }
                            )
                            ->allowedTypes(AllowedTypeEnum::ARRAY->value);
                    }
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);
    }

    public static function resolve(array &$patchNotesData): bool
    {
        $resolver = new OptionsResolver();

        $resolver
            ->define('type')
            ->required()
            ->allowedValues(...PatchNoteTypeEnum::getNames()->toArray());

        $resolver
            ->define('category')
            ->required()
            ->allowedValues(...PatchNoteCategoryEnum::getNames()->toArray());

        $resolver
            ->define('summary')
            ->default(null)
            ->allowedTypes(AllowedTypeEnum::STRING->value, AllowedTypeEnum::NULL->value);

        $resolver
            ->define('description')
            ->required()
            ->allowedTypes(AllowedTypeEnum::STRING->value);

        $patchNotesData = array_map([$resolver, 'resolve'], $patchNotesData);

        return true;
    }
}
