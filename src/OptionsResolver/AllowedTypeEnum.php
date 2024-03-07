<?php

declare(strict_types=1);

namespace App\OptionsResolver;

enum AllowedTypeEnum: string
{
    case ARRAY = 'array';
    case INTEGER = 'int';
    case STRING = 'string';
    case ARRAY_OF_STRINGS = 'string[]';
}
