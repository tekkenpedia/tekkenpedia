<?php

declare(strict_types=1);

namespace App\Parser\PatchNote;

use App\Decoder\JsonDecoder;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JsonParser
{
    public function parse(string $pathname): array
    {
        $data = JsonDecoder::decode($pathname);
        $resolver = new OptionsResolver();

        PatchNotesOptionsResolver::configure($resolver);

        return $resolver->resolve($data);
    }
}
