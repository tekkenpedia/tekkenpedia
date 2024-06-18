<?php

declare(strict_types=1);

namespace App\Command;

use App\Ffmpeg\Concat;
use Symfony\Component\Console\{
    Command\Command,
    Input\InputInterface,
    Output\OutputInterface
};
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

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
        $dir = __DIR__ . '/../../data/characters';
        $filesystem = new Filesystem();
        $files = (new Finder())->files()->in($dir);
        foreach ($files as $file) {
            $movesDir = $file->getPath() . '/moves';
//            $filesystem->mkdir($movesDir);

            $json = json_decode(file_get_contents($file->getPathname()), true, 512, JSON_THROW_ON_ERROR);

            foreach ($json['sections'] ?? [] as $section) {
                foreach ($section['moves'] ?? [] as $id => $move) {
                    $move = array_merge(['id' => $id], $move);
                    file_put_contents($movesDir . '/' . $id . '.json', json_encode($move, JSON_PRETTY_PRINT) . "\n");
                }

                foreach ($section['sections'] ?? [] as $subSection) {
                    foreach ($subSection['moves'] ?? [] as $id => $move) {
                        $move = array_merge(['id' => $id], $move);
                        file_put_contents($movesDir . '/' . $id . '.json', json_encode($move, JSON_PRETTY_PRINT) . "\n");
                    }
                }
            }
        }

        die;

        $this->concat->concat($output);

        return static::SUCCESS;
    }
}
