<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\Character\Move\Step\StepEnum;
use Twig\{
    Extension\AbstractExtension,
    TwigFilter
};

class StepExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [new TwigFilter('step_difficulty', [$this, 'stepDifficulty'])];
    }

    public function stepDifficulty(StepEnum $step): string
    {
        return match ($step) {
            StepEnum::EASY => 'easy',
            StepEnum::MEDIUM => 'medium',
            StepEnum::HARD => 'hard',
            StepEnum::IMPOSSIBLE => 'impossible',
        };
    }
}
