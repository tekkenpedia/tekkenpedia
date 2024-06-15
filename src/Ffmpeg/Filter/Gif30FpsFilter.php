<?php

declare(strict_types=1);

namespace App\Ffmpeg\Filter;

use FFMpeg\Filters\Gif\GifFilterInterface;
use FFMpeg\Media\Gif;

class Gif30FpsFilter implements GifFilterInterface
{
    public function getPriority(): int
    {
        return 1;
    }

    /** @return array<string> */
    public function apply(Gif $gif): array
    {
        return ['-r', '60'];
    }
}
