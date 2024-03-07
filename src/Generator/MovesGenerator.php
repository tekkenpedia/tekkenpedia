<?php

declare(strict_types=1);

namespace App\Generator;

use App\Move\Moves;
use Symfony\Component\Console\Output\OutputInterface;

class MovesGenerator
{
    public function __construct(private readonly Moves $moves)
    {
    }

    public function generate(OutputInterface $output): static
    {
        foreach ($this->moves->getCharactersSlugs()->toArray() as $charactersSlug) {
            $output->writeln('Generating moves for <info>' . $charactersSlug . '</info>.');

            dd($this->moves->getMoves($charactersSlug));
        }

        return $this;
    }
}
