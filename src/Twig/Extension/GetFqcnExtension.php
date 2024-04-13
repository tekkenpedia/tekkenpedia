<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\{
    Character\Move\Attack\Attack,
    Character\Move\Throw\Throw_
};
use Twig\{
    Extension\AbstractExtension,
    TwigFunction
};

class GetFqcnExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('getAttackFqcn', [$this, 'getAttackFqcn']),
            new TwigFunction('getThrowFqcn', [$this, 'getThrowFqcn'])
        ];
    }

    public function getAttackFqcn(): string
    {
        return Attack::class;
    }

    public function getThrowFqcn(): string
    {
        return Throw_::class;
    }
}
