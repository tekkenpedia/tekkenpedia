<?php

declare(strict_types=1);

namespace App\Command;

use App\Generator\MovesGenerator;
use Symfony\Component\Console\{
    Command\Command,
    Input\InputInterface,
    Output\OutputInterface
};

class GenerateMovesCommand extends Command
{
    public static function getDefaultName(): ?string
    {
        return 'generate:moves';
    }

    public static function getDefaultDescription(): ?string
    {
        return 'Generate moves for all characters';
    }

    public function __construct(private readonly MovesGenerator $movesGenerator)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->movesGenerator->generate($output);

        return static::SUCCESS;
    }
}
