<?php

declare(strict_types=1);

namespace App\Generator\Html;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;

readonly class IndexHtmlGenerator
{
    private string $renderPath;

    public function __construct(string $projectDir, private Environment $twig)
    {
        $this->renderPath = $projectDir . '/docs';
    }

    public function generate(OutputInterface $output): static
    {
        $renderPathname = $this->renderPath . '/index.html';

        $output->writeln('Generating <info>' . $renderPathname . '</info>.');

        (new Filesystem())->dumpFile(
            $renderPathname,
            $this->twig->render('index.html.twig')
        );

        return $this;
    }
}
