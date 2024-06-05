<?php

declare(strict_types=1);

namespace App\Parser\Character\Move;

use App\OptionsResolver\AllowedTypeEnum;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SectionsOptionsResolver
{
    /** @param array<mixed> $data */
    public static function configure(OptionsResolver $resolver, array &$data): void
    {
        $resolver
            ->define('sections')
            ->default(
                static function (OptionsResolver $resolver) use (&$data): void {
                    /** @var string $sectionName */
                    foreach (array_keys($data['sections'] ?? []) as $sectionName) {
                        $resolver
                            ->define($sectionName)
                            ->default(
                                static function (OptionsResolver $sectionResolver) use ($data, $sectionName): void {
                                    SectionOptionsResolver::configure(
                                        $sectionResolver,
                                        $data['sections'][$sectionName]
                                    );
                                }
                            )
                            ->allowedTypes(AllowedTypeEnum::ARRAY->value);
                    }
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);
    }
}
