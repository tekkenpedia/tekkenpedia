<?php

declare(strict_types=1);

namespace App\Generator\Html;

use App\{
    Character\Character,
    Character\Section\Section,
    Collection\Character\CharacterCollection};
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;

readonly class DefenseHtmlGenerator
{
    private string $renderRootPath;

    public function __construct(string $projectDir, private Environment $twig, private Filesystem $filesystem)
    {
        $this->renderRootPath = $projectDir . '/docs/characters';
    }

    public function generate(CharacterCollection $characters, OutputInterface $output): static
    {
        foreach ($characters->toArray() as $character) {
            $this->clear($character, $output);
            if ($character->sections->hasDefenseMoves()) {
                $this->generateIndex($character, $output);
                foreach ($character->sections->toArray() as $section) {
                    $this->generateMoves($character, $section, $output);
                }
            }
        }

        return $this;
    }

    private function clear(Character $character, OutputInterface $output): static
    {
        $rootPath = $this->getRenderPath($character);
        if (is_dir($rootPath)) {
            $output->writeln('Removing <info>' . $rootPath . '</info>.');
            $this->filesystem->remove($rootPath);
        }

        return $this;
    }

    private function generateIndex(Character $character, OutputInterface $output): static
    {
        $renderPathname = $this->getRenderPath($character) . '/index.html';
        $output->writeln('Generating <info>' . $renderPathname . '</info>.');

        $this->filesystem->dumpFile(
            $renderPathname,
            $this->twig->render(
                'characters/defense/index/index.html.twig',
                ['character' => $character]
            )
        );

        return $this;
    }

    private function generateMoves(Character $character, Section $section, OutputInterface $output): static
    {
        $rootPath = $this->getRenderPath($character);

        foreach ($section->moves->toArray() as $move) {
            $renderPathname = $rootPath . '/' . $move->slug . '.html';
            $output->writeln('Generating <info>' . $renderPathname . '</info>.');

            $this->filesystem->dumpFile(
                $renderPathname,
                $this->twig->render(
                    'characters/defense/move/move.html.twig',
                    [
                        'character' => $character,
                        'move' => $move
                    ]
                )
            );
        }

        foreach ($section->sections->toArray() as $subSection) {
            $this->generateMoves($character, $subSection, $output);
        }

        return $this;
    }

    private function getRenderPath(Character $character): string
    {
        return $this->renderRootPath . '/' . $character->slug . '/defense';
    }
}
