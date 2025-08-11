<?php

declare(strict_types=1);

namespace App\Generator;

readonly class TokenGenerator extends AbstractGenerator
{
    protected function getTemplateFilename(): string
    {
        return 'token';
    }
}
