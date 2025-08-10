<?php

declare(strict_types=1);

namespace App\Character\Move\Attack;

use App\Character\CharacterSlugEnum;
use Symfony\Component\Uid\Uuid;

readonly class AddAttack
{
    public function __construct(private string $projectDir)
    {
    }

    public function add(
        CharacterSlugEnum $characterSlug,
        string $inputs,
        PropertyEnum $property,
        int $blockFramesMin,
        ?int $blockFramesMax
    ): Uuid {
        $id = Uuid::v4();

        $data = [
            'inputs' => $inputs,
            'visibility' => ['punish' => true],
            'property' => $property->name,
            'frames' => [
                'startup' => [
                    'min' => 7777
                ],
                'block' => [
                    'min' => $blockFramesMin
                ],
                'normal-hit' => 7777,
                'counter-hit' => 7777
            ]
        ];
        if (is_int($blockFramesMax)) {
            $data['frames']['block']['max'] = $blockFramesMax;
        }

        file_put_contents(
            $this->projectDir . '/data/characters/' . $characterSlug->value . '/moves/' . $id->toRfc4122() . '.json',
            json_encode($data, JSON_PRETTY_PRINT) . "\n"
        );

        return $id;
    }
}
