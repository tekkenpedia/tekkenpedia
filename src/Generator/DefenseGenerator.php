<?php

declare(strict_types=1);

namespace App\Generator;

use App\Character\CharacterFactory;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;

readonly class DefenseGenerator
{
    private string $renderPath;

    public function __construct(
        string $projectDir,
        private CharacterFactory $characterFactory,
        private Environment $twig
    ) {
        $this->renderPath = $projectDir . '/docs/characters';
    }

    public function generate(OutputInterface $output): static
    {
        $filesystem = new Filesystem();

        foreach ($this->characterFactory->createAll()->toArray() as $character) {
            $defensePath = $this->renderPath . '/' . $character->slug . '/defense';
            if (is_dir($defensePath)) {
                $output->writeln('Removing <info>' . $defensePath . '</info>.');
                $filesystem->remove($defensePath);
            }

            $renderPathname = $defensePath . '/index.html';
            $output->writeln('Generating <info>' . $renderPathname . '</info>.');

            $filesystem->dumpFile(
                $renderPathname,
                $this->twig->render(
                    'characters/defense/index/index.html.twig',
                    ['character' => $character]
                )
            );
        }

        return $this;
    }
}
