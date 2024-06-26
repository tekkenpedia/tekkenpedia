<?php

declare(strict_types=1);

namespace App\Ffmpeg;

use App\{
    Collection\Symfony\Component\Finder\SplFileInfoCollection,
    Exception\AppException
};
use FFMpeg\{
    FFMpeg,
    Media\Video
};
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\{
    Finder,
    SplFileInfo
};

class Concat
{
    private readonly string $path;

    public function __construct(string $projectDir)
    {
        $this->path = $projectDir . '/var/concat';
        if (is_dir($this->path) === false) {
            (new Filesystem())->mkdir($this->path);
        }
    }

    public function concat(OutputInterface $output): string
    {
        $outputPathname = $this->path . '/concat.mp4';
        if (file_exists($outputPathname)) {
            (new Filesystem())->remove($outputPathname);
        }

        $finder = $this->createFinder();

        if (count($finder) <= 0) {
            throw new AppException('No videos to concat found in ' . $this->path . '.');
        }

        /** @var Video $video */
        $video = FFMpeg::create()->open($finder->getIterator()->current()->getPathname());

        $video
            ->concat($this->findFiles($finder, $output)->getPathnames()->toArray())
            ->saveFromSameCodecs($outputPathname);

        return $outputPathname;
    }

    private function createFinder(): Finder
    {
        return (new Finder())
            ->in($this->path)
            ->files()
            ->sortByName();
    }

    private function findFiles(Finder $finder, OutputInterface $output): SplFileInfoCollection
    {
        $return = new SplFileInfoCollection();
        foreach ($finder as $file) {
            if ($file->getFilename() !== 'concat.mp4') {
                $return->add($file);
                $output->writeln('File: <info>' . $file->getBasename() . '</info>.');
            }
        }

        return $return;
    }
}
