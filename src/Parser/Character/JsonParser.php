<?php

declare(strict_types=1);

namespace App\Parser\Character;

use App\Exception\AppException;

class JsonParser
{
    /** @return TCharacter */
    public function getData(string $pathname): array
    {
        $data = $this->decodeJson($pathname);

        return (new CharacterOptionsResolver($data))->resolve($data);
    }

    /** @return array<mixed> */
    private function decodeJson(string $pathname): array
    {
        if (is_readable($pathname) === false) {
            throw new AppException('File "' . $pathname . '" does not exists or is not readable.');
        }

        $json = file_get_contents($pathname);
        if (is_string($json) === false) {
            throw new AppException('File "' . $pathname . '" could not be read.');
        }

        $return = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        if (is_array($return) === false) {
            throw new AppException('Invalid json format for ' . $pathname . '.');
        }

        return $return;
    }
}
