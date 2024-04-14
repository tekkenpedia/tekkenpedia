<?php

declare(strict_types=1);

namespace App\Parser\Character;

use App\{
    OptionsResolver\AllowedTypeEnum,
    Parser\Character\Move\MoveOptionsResolver};
use Symfony\Component\OptionsResolver\OptionsResolver;

class SectionOptionsResolver
{
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

        $resolver
            ->define('sections')
            ->default(
                function (OptionsResolver $resolver) use (&$data): void {
                    foreach (array_keys($data['sections'] ?? []) as $sectionName) {
                        $resolver
                            ->define($sectionName)
                            ->default(
                                static function(OptionsResolver $sectionResolver) use (&$data, $sectionName): void {
                                    static::configure($sectionResolver, $data['sections'][$sectionName]);
                                }
                            )
                            ->allowedTypes(AllowedTypeEnum::ARRAY->value);
                    }
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);
    }
}
