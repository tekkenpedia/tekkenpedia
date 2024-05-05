<?php

declare(strict_types=1);

namespace App\Generator\Html;

use App\{
    Collection\PatchNote\PatchNotesCollection,
    PatchNote\PatchNotes,
    PatchNote\PatchNotesFactory};
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;

readonly class PatchNotesHtmlGenerator
{
    private string $renderRootPath;

    public function __construct(
        string $projectDir,
        private Environment $twig,
        private PatchNotesFactory $patchNotesFactory,
        private Filesystem $filesystem
    ) {
        $this->renderRootPath = $projectDir . '/docs/patch-notes';
    }

    public function generate(OutputInterface $output): static
    {
        $patchsNotes = $this->patchNotesFactory->create();

        $this->generateIndex($patchsNotes, $output);

        foreach ($patchsNotes->toArray() as $patchNotes) {
            $this->clear($patchNotes, $output);
        }

        return $this;
    }

    private function clear(PatchNotes $patchNotes, OutputInterface $output): static
    {
        $renderPath = $this->getRenderPath($patchNotes);
        if (is_dir($renderPath)) {
            $output->writeln('Removing <info>' . $renderPath . '</info>.');
            $this->filesystem->remove($renderPath);
        }

        return $this;
    }

    private function generateIndex(PatchNotesCollection $patchNotes, OutputInterface $output): static
    {
        $renderPathname = $this->renderRootPath . '/index.html';
        $output->writeln('Generating <info>' . $renderPathname . '</info>.');

        $this->filesystem->dumpFile(
            $renderPathname,
            $this->twig->render(
                'patch-notes/index.html.twig',
                ['patchNotes' => $patchNotes]
            )
        );

        return $this;
    }

    private function getRenderPath(PatchNotes $patchNotes): string
    {
        return $this->renderRootPath . '/' . $patchNotes->name;
    }
}
