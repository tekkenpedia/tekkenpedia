<?php

declare(strict_types=1);

namespace App\Ffmpeg;

use App\{
    Collection\SplFileInfoCollection,
    Ffmpeg\Filter\Gif30FpsFilter
};
use FFMpeg\{
    Coordinate\Dimension,
    Coordinate\TimeCode,
    FFMpeg
};
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class Convert
{
    private readonly string $path;

    public function __construct(string $projectDir)
    {
        $this->path = $projectDir . '/var/convert';
        if (is_dir($this->path) === false) {
            (new Filesystem())->mkdir($this->path);
        }
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function convert(OutputInterface $output): static
    {
        $videos = $this->findVideos();
        if ($videos->count() <= 0) {
            throw new \Exception('No videos to convert found in ' . $this->getPath() . '.');
        }

        $dimensions = new Dimension(320, 180);
        $timeCode = TimeCode::fromSeconds(0);

        foreach ($videos->toArray() as $video) {
            $output->writeln('Converting <info>' . $video->getFilename() . '</info>.');

            $outputPathname = $this->getPath() . '/' . $video->getBasename('.' . $video->getExtension()) . '.gif';
            if (file_exists($outputPathname)) {
                $output->writeln('  Removing <comment>' . basename($outputPathname) . '</comment>.');
                (new Filesystem())->remove($outputPathname);
            }

            $ffmepg = FFMpeg::create();
            $ffmpegVideo = $ffmepg->open($video->getPathname());
            $ffmpegVideo
                ->gif($timeCode, $dimensions)
                ->addFilter(new Gif30FpsFilter())
                ->save($outputPathname);

            $output->writeln(
                '  <info>'
                . $video->getFilename()
                . '</info> has been converted to <info>'
                . basename($outputPathname)
                . '</info>.'
            );
        }

        return $this;
    }

    private function findVideos(): SplFileInfoCollection
    {
        $finder = (new Finder())
            ->in($this->path)
            ->files()
            ->name('*.mp4')
            ->sortByName();

        $return = new SplFileInfoCollection();
        foreach ($finder as $file) {
            $return->add($file);
        }

        return $return;
    }
}
