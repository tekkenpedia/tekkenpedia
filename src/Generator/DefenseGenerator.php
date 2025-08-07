<?php

declare(strict_types=1);

namespace App\Generator;

use App\{
    Character\Character,
    Character\CharacterFactory,
    Character\Move\Attack\Attack,
    Character\Section\Section,
    Collection\Character\CharacterCollection,
    Tidy\Tidy
};
use Symfony\Component\Console\Helper\ProgressBar;
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

    public function generate(ProgressBar $progressBar): static
    {
        $filesystem = new Filesystem();

        foreach ($this->characterFactory->createAll()->toArray() as $character) {
            $this
                ->clear($character, $filesystem)
                ->generateIndex($character, $filesystem, $progressBar);

            foreach ($character->sections->toArray() as $section) {
                $this->generateMoves($character, $section, $filesystem, $progressBar);
            }
        }

        $progressBar->finish();

        return $this;
    }

    private function clear(Character $character, Filesystem $filesystem): static
    {
        $rootPath = $this->getRootPath($character);
        if (is_dir($rootPath)) {
            $filesystem->remove($rootPath);
        }

        return $this;
    }

    private function generateIndex(Character $character, Filesystem $filesystem, ProgressBar $progressBar): static
    {
        $renderPathname = $this->getRootPath($character) . '/index.html';

        $filesystem->dumpFile(
            $renderPathname,
            $this->twig->render(
                'characters/defense/index/index.html.twig',
                ['character' => $character]
            )
        );

        $progressBar->advance();

        Tidy::format($renderPathname);

        return $this;
    }

    private function generateMoves(
        Character $character,
        Section $section,
        Filesystem $filesystem,
        ProgressBar $progressBar
    ): static {
        $rootPath = $this->getRootPath($character);

        foreach ($section->moves->toArray() as $move) {
            if ($move instanceof Attack && is_string($move->masterId)) {
                continue;
            }

            $renderPathname = $rootPath . '/' . $move->getSlug() . '.html';
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

            $progressBar->advance();

            Tidy::format($renderPathname);
        }

        foreach ($section->sections->toArray() as $subSection) {
            $this->generateMoves($character, $subSection, $filesystem, $progressBar);
        }

        return $this;
    }

    private function getRootPath(Character $character): string
    {
        return $this->renderPath . '/' . $character->slug . '/defense';
    }
}
