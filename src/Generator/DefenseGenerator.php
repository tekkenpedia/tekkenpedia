<?php

declare(strict_types=1);

namespace App\Generator;

use App\{
    Character\Character,
    Character\CharacterFactory,
    Character\Move\Attack\Attack,
    Character\Section\Section,
    Tidy\Tidy};
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
            $this
                ->clear($character, $filesystem, $output)
                ->generateIndex($character, $filesystem, $output);

            foreach ($character->sections->toArray() as $section) {
                $this->generateMoves($character, $section, $filesystem, $output);
            }
        }

        return $this;
    }

    private function clear(Character $character, Filesystem $filesystem, OutputInterface $output): static
    {
        $rootPath = $this->getRootPath($character);
        if (is_dir($rootPath)) {
            $output->writeln('Removing <info>' . $rootPath . '</info>.');
            $filesystem->remove($rootPath);
        }

        return $this;
    }

    private function generateIndex(Character $character, Filesystem $filesystem, OutputInterface $output): static
    {
        $renderPathname = $this->getRootPath($character) . '/index.html';
        $output->writeln('Generating <info>' . $renderPathname . '</info>.');

        $filesystem->dumpFile(
            $renderPathname,
            $this->twig->render(
                'characters/defense/index/index.html.twig',
                ['character' => $character]
            )
        );

        Tidy::format($renderPathname);

        return $this;
    }

    private function generateMoves(
        Character $character,
        Section $section,
        Filesystem $filesystem,
        OutputInterface $output
    ): static {
        $rootPath = $this->getRootPath($character);

        foreach ($section->moves->toArray() as $move) {
            if ($move instanceof Attack && is_string($move->masterId)) {
                continue;
            }

            $renderPathname = $rootPath . '/' . $move->slug . '.html';
            $output->writeln('Generating <info>' . $renderPathname . '</info>.');

            $filesystem->dumpFile(
                $renderPathname,
                $this->twig->render(
                    'characters/defense/move/move.html.twig',
                    [
                        'character' => $character,
                        'move' => $move
                    ]
                )
            );

            Tidy::format($renderPathname);
        }

        foreach ($section->sections->toArray() as $subSection) {
            $this->generateMoves($character, $subSection, $filesystem, $output);
        }

        return $this;
    }

    private function getRootPath(Character $character): string
    {
        return $this->renderPath . '/' . $character->slug . '/defense';
    }
}
