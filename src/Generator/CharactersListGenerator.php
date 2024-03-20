<?php

declare(strict_types=1);

namespace App\Generator;

use App\Character\CharacterFactory;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;

readonly class CharactersListGenerator
{
    private string $renderPath;

    public function __construct(string $projectDir, private CharacterFactory $characterFactory, private Environment $twig)
    {
        $this->renderPath = $projectDir . '/docs';
    }

    public function generate(OutputInterface $output): static
    {
        $renderPathname = $this->renderPath . '/index.html';

        $output->writeln('Generating <info>' . $renderPathname . '</info>.');

        (new Filesystem())->dumpFile(
            $renderPathname,
            $this->twig->render(
                'characters/list.html.twig',
                ['characters' => $this->characterFactory->createAll()]
            )
        );

        return $this;
    }
}
