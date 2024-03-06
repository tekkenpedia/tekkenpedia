<?php

declare(strict_types=1);

namespace App\Command;

use App\Ffmpeg\Convert;
use Symfony\Component\Console\{
    Command\Command,
    Input\InputInterface,
    Output\OutputInterface
};

class ConvertVideoGifCommand extends Command
{
    public static function getDefaultName(): ?string
    {
        return 'convert:video:gif';
    }

    public static function getDefaultDescription(): ?string
    {
        return 'Convert videos to a gif in var/convert';
    }

    public function __construct(private readonly Convert $convert)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->convert->convert($output);

        return static::SUCCESS;
    }
}
