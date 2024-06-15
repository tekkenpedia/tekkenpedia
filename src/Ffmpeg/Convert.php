<?php

declare(strict_types=1);

namespace App\Ffmpeg;

use App\{
    Collection\Symfony\Component\Finder\SplFileInfoCollection,
    Exception\AppException,
    Ffmpeg\Filter\Gif30FpsFilter
};
use FFMpeg\{
    Coordinate\Dimension,
    Coordinate\TimeCode,
    FFMpeg,
    Media\Video
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

    public function convert(\SplFileInfo $video, OutputInterface $output): static
    {
//        $videos = $this->findVideos();
//        if ($videos->count() <= 0) {
//            throw new AppException('No videos to convert found in ' . $this->getPath() . '.');
//        }

        $dimensions = [
            'tekkenpedia' => new Dimension(320, 180),
            '360p' => new Dimension(640, 360),
            '480p' => new Dimension(852, 480),
            '720p' => new Dimension(1280, 720),
            '1080p' => new Dimension(1920, 1080),
            '1440p' => new Dimension(2560, 1440),
            '2160p' => new Dimension(3840, 2160),
        ];
        $timeCode = TimeCode::fromSeconds(0);

        foreach ($dimensions as $name => $dimension) {
//        foreach ($videos->toArray() as $video) {
            $output->writeln('Converting <info>' . $video->getFilename() . '</info>.');

            $outputPathname = $this->getPath() . '/' . $video->getBasename('.' . $video->getExtension()) . '-' . $name . '.gif';
            if (file_exists($outputPathname)) {
                $output->writeln('  Removing <comment>' . basename($outputPathname) . '</comment>.');
                (new Filesystem())->remove($outputPathname);
            }

            $ffmepg = FFMpeg::create();
            /** @var Video $ffmpegVideo */
            $ffmpegVideo = $ffmepg->open($video->getPathname());
            $ffmpegVideo
                ->gif($timeCode, $dimension)
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
