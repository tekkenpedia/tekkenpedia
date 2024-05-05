<?php

declare(strict_types=1);

namespace App\Command;

use App\{
    Character\CharacterFactory,
    Generator\Html\CharactersListHtmlGenerator,
    Generator\Html\DefenseHtmlGenerator,
    Generator\Html\IndexHtmlGenerator,
    Generator\Html\PatchNotesHtmlGenerator
};
use Symfony\Component\Console\{
    Command\Command,
    Input\InputInterface,
    Output\OutputInterface
};

class GenerateHtmlCommand extends Command
{
    public static function getDefaultName(): ?string
    {
        return 'generate:html';
    }

    public static function getDefaultDescription(): ?string
    {
        return 'Generate static HTML';
    }

    public function __construct(
        private readonly IndexHtmlGenerator $indexHtmlGenerator,
        private readonly CharacterFactory $characterFactory,
        private readonly CharactersListHtmlGenerator $charactersListHtmlGenerator,
        private readonly DefenseHtmlGenerator $defenseHtmlGenerator,
        private readonly PatchNotesHtmlGenerator $patchNotesHtmlGenerator
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $characters = $this->characterFactory->createAll();

        $this->indexHtmlGenerator->generate($output);
        $this->charactersListHtmlGenerator->generate($characters, $output);
        $this->defenseHtmlGenerator->generate($characters, $output);
        $this->patchNotesHtmlGenerator->generate($output);

        return static::SUCCESS;
    }
}
