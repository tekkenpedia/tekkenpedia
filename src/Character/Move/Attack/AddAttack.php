<?php

declare(strict_types=1);

namespace App\Character\Move\Attack;

use App\{
    Character\CharacterSlugEnum,
    Character\Move\UsedEnum,
    Collection\Character\Move\Attack\DefenseEnumCollection,
    Collection\Character\Move\Attack\PropertyEnumCollection
};
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Uid\Uuid;

readonly class AddAttack
{
    public function __construct(private string $projectDir)
    {
    }

    public function add(
        CharacterSlugEnum $characterSlug,
        string $inputs,
        UsedEnum $used,
        DefenseEnumCollection $defenses,
        PropertyEnumCollection $properties,
        int $blockFramesMin,
        ?int $blockFramesMax
    ): Uuid {
        $id = Uuid::v4();

        $data = [
            'inputs' => $inputs,
            'visibility' => ['punish' => true],
            'used' => $used->name,
            'defenses' => $defenses->toNamesArray(),
            'properties' => $properties->getStringValues()->toArray(),
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

        $directory = $this->projectDir . '/data/characters/' . $characterSlug->value . '/moves';
        if (is_dir($directory) === false) {
            (new Filesystem())->mkdir($directory);
        }

        file_put_contents(
            $directory . '/' . $id->toRfc4122() . '.json',
            json_encode($data, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR) . "\n"
        );

        return $id;
    }
}
