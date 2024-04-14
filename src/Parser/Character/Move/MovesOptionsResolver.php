<?php

declare(strict_types=1);

namespace App\Parser\Character\Move;

use App\OptionsResolver\AllowedTypeEnum;
use App\Parser\Character\SectionOptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovesOptionsResolver
{
    public static function configure(OptionsResolver $resolver, array &$data): void
    {
        $resolver
            ->define('moves')
            ->default(
                static function(OptionsResolver $resolver) use (&$data): void {
                    foreach (array_keys($data['moves'] ?? []) as $sectionName) {
                        $resolver
                            ->define($sectionName)
                            ->default(
                                static function (OptionsResolver $sectionResolver) use ($data, $sectionName): void {
                                    SectionOptionsResolver::configure($sectionResolver, $data['moves'][$sectionName]);
                                }
                            )
                            ->allowedTypes(AllowedTypeEnum::ARRAY->value);
                    }
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);
    }
}
