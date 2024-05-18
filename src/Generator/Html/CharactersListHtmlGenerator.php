<?php

declare(strict_types=1);

namespace App\Generator\Html;

use App\Collection\Character\CharacterCollection;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;

readonly class CharactersListHtmlGenerator
{
    private string $renderPath;

    public function __construct(string $projectDir, private Environment $twig)
    {
        $this->renderPath = $projectDir . '/docs/characters';
    }

    public function generate(CharacterCollection $characters, OutputInterface $output): static
    {
        $renderPathname = $this->renderPath . '/index.html';

        $output->writeln('Generating <info>' . $renderPathname . '</info>.');

        (new Filesystem())->dumpFile(
            $renderPathname,
            $this->twig->render(
                'characters/list.html.twig',
                ['characters' => $characters]
            )
        );

        return $this;
    }
}
