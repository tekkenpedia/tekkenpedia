<?php

declare(strict_types=1);

namespace App\Tidy;

use App\Exception\AppException;
use Symfony\Component\Process\Process;

class Tidy
{
    public static function format(string $htmlPathname): void
    {
        $process = new Process(
            [
                'tidy',
                '-modify',
                '-indent',
                '--indent-spaces',
                '4',
                '-wrap',
                '1000',
                '--tidy-mark',
                'no',
                $htmlPathname
            ]
        );
        $process->run();

        // Si tidy a un warning, il retourne un exit code à 1, et pas moyen de changer ça
        if ($process->getExitCode() !== 1) {
            throw new AppException($process->getOutput() . "\n" . $process->getErrorOutput());
        }
    }
}
