<?php

declare(strict_types=1);

namespace App\Command;

use App\{
    Generator\AddMoveGenerator,
    Generator\CharactersListGenerator,
    Generator\DefenseGenerator,
    Generator\PunishGenerator
};
use Symfony\Component\Console\{
    Command\Command,
    Helper\ProgressBar,
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
        private readonly DefenseGenerator $defenseGenerator,
        private readonly PunishGenerator $punishGenerator,
        private readonly AddMoveGenerator $addMoveGenerator
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->charactersListGenerator->generate($output);

        $output->writeln('Generating defenses...');
        $this->defenseGenerator->generate(new ProgressBar($output));
        $output->writeln('');

        $output->writeln('Generating punishs...');
        $this->punishGenerator->generate(new ProgressBar($output));
        $output->writeln('');

        $this->addMoveGenerator->generate($output);

        return static::SUCCESS;
    }
}
