<?php

declare(strict_types=1);

namespace App\Command;

use App\Ffmpeg\Concat;
use Symfony\Component\Console\{
    Command\Command,
    Input\InputInterface,
    Output\OutputInterface
};

class ConcatVideosCommand extends Command
{
    public static function getDefaultName(): ?string
    {
        return 'concat:videos';
    }

    public static function getDefaultDescription(): ?string
    {
        return 'Concat videos in var/concat';
    }

    public function __construct(private readonly Concat $concat)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->concat->concat($output);

        return static::SUCCESS;
    }
}
