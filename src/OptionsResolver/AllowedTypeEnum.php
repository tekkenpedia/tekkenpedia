<?php

declare(strict_types=1);

namespace App\OptionsResolver;

enum AllowedTypeEnum: string
{
    case ARRAY = 'array';
    case ARRAY_OF_STRINGS = 'string[]';
    case BOOLEAN = 'boolean';
    case INTEGER = 'int';
    case NULL = 'null';
    case STRING = 'string';
}
