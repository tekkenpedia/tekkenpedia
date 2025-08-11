<?php

declare(strict_types=1);

namespace App\Generator;

use App\{
    Character\CharacterFactory,
    Character\Move\Attack\PropertyEnum
};
use Twig\Environment;

readonly class AddMoveGenerator extends AbstractGenerator
{
    public function __construct(
        string $projectDir,
        private Environment $twig,
        private CharacterFactory $characterFactory
    ) {
        parent::__construct($projectDir, $this->twig);
    }

    protected function getTemplateFilename(): string
    {
        return 'add-move';
    }

    protected function getTemplateContext(): array
    {
        return [
            'characters' => $this->characterFactory->createAll(),
            'properties' => PropertyEnum::cases()
        ];
    }
}
