<?php

declare(strict_types=1);

namespace App\Generator;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;

abstract readonly class AbstractGenerator
{
    abstract protected function getTemplateFilename(): string;

    private string $renderPath;

    public function __construct(string $projectDir, private Environment $twig)
    {
        $this->renderPath = $projectDir . '/docs';
    }

    public function generate(OutputInterface $output): static
    {
        $renderPathname = $this->renderPath . '/' . $this->getTemplateFilename() . '.html';

        $output->writeln('Generating <info>' . $renderPathname . '</info>.');

        (new Filesystem())->dumpFile(
            $renderPathname,
            $this->twig->render(
                $this->getTemplateFilename() . '.html.twig',
                $this->getTemplateContext()
            )
        );

        return $this;
    }

    /** @return mixed[] */
    protected function getTemplateContext(): array
    {
        return [];
    }
}
