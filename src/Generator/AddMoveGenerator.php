<?php

declare(strict_types=1);

namespace App\Generator;

use App\{
    Character\CharacterFactory,
    Character\Move\Attack\PropertyEnum
};
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;

readonly class AddMoveGenerator
{
    private string $renderPath;

    public function __construct(
        string $projectDir,
        private CharacterFactory $characterFactory,
        private Environment $twig
    ) {
        $this->renderPath = $projectDir . '/docs';
    }

    public function generate(OutputInterface $output): static
    {
        $renderPathname = $this->renderPath . '/add-move.html';

        $output->writeln('Generating <info>' . $renderPathname . '</info>.');

        (new Filesystem())->dumpFile(
            $renderPathname,
            $this->twig->render(
                'add-move.html.twig',
                [
                    'characters' => $this->characterFactory->createAll(),
                    'properties' => PropertyEnum::cases()
                ]
            )
        );

        return $this;
    }
}
