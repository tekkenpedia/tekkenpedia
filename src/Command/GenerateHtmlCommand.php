<?php

declare(strict_types=1);

namespace App\Command;

use App\{
    Generator\CharactersListGenerator,
    Generator\DefenseGenerator
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
        private readonly CharactersListGenerator $charactersListGenerator,
        private readonly DefenseGenerator $defenseGenerator
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->charactersListGenerator->generate($output);
        $this->defenseGenerator->generate($output);

        return static::SUCCESS;
    }
}
