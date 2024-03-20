<?php

declare(strict_types=1);

namespace App\Generator;

use App\Character\Factory;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;

readonly class DefenseGenerator
{
    private string $renderPath;

    public function __construct(string $projectDir, private Factory $factory, private Environment $twig)
    {
        $this->renderPath = $projectDir . '/docs/characters';
    }

    public function generate(OutputInterface $output): static
    {
        $filesystem = new Filesystem();

        foreach ($this->factory->getSlugs()->toArray() as $charactersSlug) {
            $output->writeln('Generating defense for <info>' . $charactersSlug . '</info>.');

            $filesystem->dumpFile(
                $this->renderPath . '/' . $charactersSlug . '/defense/index.html',
                $this->twig->render(
                    'characters/defense/index/index.html.twig',
                    [
                        'characterSlug' => $charactersSlug,
                        'character' => $this->factory->create($charactersSlug)
                    ]
                )
            );
        }

        return $this;
    }
}
