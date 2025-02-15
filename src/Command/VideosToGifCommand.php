<?php

declare(strict_types=1);

namespace App\Command;

use App\{
    Ffmpeg\Concat,
    Ffmpeg\Convert
};
use Symfony\Component\Console\{
    Command\Command,
    Input\InputInterface,
    Output\OutputInterface
};
use Symfony\Component\Filesystem\Filesystem;

class VideosToGifCommand extends Command
{
    public static function getDefaultName(): ?string
    {
        return 'videos:to:gif';
    }

    public static function getDefaultDescription(): ?string
    {
        return 'Concat videos in var/concat, then convert it to a gif in var/convert';
    }

    public function __construct(private readonly Concat $concat, private readonly Convert $convert)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $concatPathname = $this->concat->concat($output);

        $filesystem = new Filesystem();
        $convertPathname = $this->convert->getPath() . '/convert.mp4';
        $filesystem->copy($concatPathname, $convertPathname);
        $filesystem->remove($concatPathname);

        $this->convert->convert($output);

        $filesystem->remove($convertPathname);

        return static::SUCCESS;
    }
}
