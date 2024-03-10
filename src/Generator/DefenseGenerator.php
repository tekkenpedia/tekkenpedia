<?php

declare(strict_types=1);

namespace App\Generator;

use App\Move\Moves;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;

readonly class DefenseGenerator
{
    private string $renderPath;

    public function __construct(string $projectDir, private Moves $moves, private Environment $twig)
    {
        $this->renderPath = $projectDir . '/docs/characters';
    }

    public function generate(OutputInterface $output): static
    {
        $filesystem = new Filesystem();

        foreach ($this->moves->getCharactersSlugs()->toArray() as $charactersSlug) {
            $output->writeln('Generating moves for <info>' . $charactersSlug . '</info>.');

            $filesystem->dumpFile(
                $this->renderPath . '/' . $charactersSlug . '/defense.html',
                $this->twig->render(
                    'characters/defense.html.twig',
                    [
                        'characterSlug' => $charactersSlug,
                        'sections' => $this->moves->getSections($charactersSlug)
                    ]
                )
            );
        }

        return $this;
    }
}
