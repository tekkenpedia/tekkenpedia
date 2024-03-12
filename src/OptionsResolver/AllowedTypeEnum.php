<?php

declare(strict_types=1);

namespace App\OptionsResolver;

enum AllowedTypeEnum: string
{
    case NULL = 'null';
    case ARRAY = 'array';
    case ARRAY_OF_STRINGS = 'string[]';
    case ARRAY_OF_INTEGERS = 'integer[]';
    case INTEGER = 'int';
    case STRING = 'string';
}
