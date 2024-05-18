<?php

declare(strict_types=1);

namespace App\Parser\PatchNote;

use App\OptionsResolver\AllowedTypeEnum;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatchNotesOptionsResolver
{
    public static function configure(OptionsResolver $resolver): void
    {
        $resolver
            ->define('id')
            ->required()
            ->allowedTypes(AllowedTypeEnum::STRING->value);

        $resolver
            ->define('name')
            ->required()
            ->allowedTypes(AllowedTypeEnum::STRING->value);

        $resolver
            ->define('releaseDates')
            ->required()
            ->allowedTypes(AllowedTypeEnum::STRING->value);
    }
}
