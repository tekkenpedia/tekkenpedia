<?php

declare(strict_types=1);

namespace App\Command;

use App\{
    Exception\AppException,
    Ffmpeg\Concat,
    Ffmpeg\Convert
};
use Symfony\Component\Console\{
    Command\Command,
    Input\InputArgument,
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
        return
            'Concat videos in var/concat, convert it to a gif in var/convert'
            . ' then copy to docs/images/characters/<name>/punish/<id>.gif';
    }

    public function __construct(
        private readonly Concat $concat,
        private readonly Convert $convert,
        private readonly string $projectDir
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('characterSlug', InputArgument::REQUIRED, 'Character slug')
            ->addArgument('moveId', InputArgument::REQUIRED, 'Move id');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string $characterSlug */
        $characterSlug = $input->getArgument('characterSlug');
        /** @var string $moveId */
        $moveId = $input->getArgument('moveId');

        $concatPathname = $this->concat->concat($output);
        $filesystem = new Filesystem();

        $convertPathname = $this->convert->getPath() . '/convert.mp4';
        $filesystem->copy($concatPathname, $convertPathname);

        $this->convert->convert($output);
        $convertResultPathname = $this->convert->getPath() . '/convert.gif';

        $imagePath = 'docs/images/characters/' . $characterSlug . '/punish';
        $imagePathname = $imagePath . '/' . $moveId . '.gif';
        $filesystem->mkdir($this->projectDir . '/' . $imagePath);
        if (file_exists($imagePathname)) {
            throw new AppException('Image ' . $imagePathname . ' already exists.');
        }
        $filesystem->rename($convertResultPathname, $imagePathname);
        $output->writeln(basename($convertResultPathname) . ' has been moved to <info>' . $imagePathname . '</info>.');

        $filesystem->remove($concatPathname);
        $filesystem->remove($convertPathname);

        return static::SUCCESS;
    }
}
