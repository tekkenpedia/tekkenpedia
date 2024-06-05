<?php

declare(strict_types=1);

namespace App\Parser\Character;

use App\{
    OptionsResolver\AllowedTypeEnum,
    Parser\Character\Move\SectionsOptionsResolver
};
use Symfony\Component\OptionsResolver\OptionsResolver;

class CharacterOptionsResolver extends OptionsResolver
{
    /** @param array<mixed> $data */
    public function __construct(array &$data)
    {
        $this
            ->define('name')
            ->required()
            ->allowedTypes(AllowedTypeEnum::STRING->value);

        $this
            ->define('slug')
            ->required()
            ->allowedTypes(AllowedTypeEnum::STRING->value);

        $this
            ->define('select-screen')
            ->default(
                static function (OptionsResolver $selectScreenResolver): void {
                    SelectScreenOptionsResolver::configure($selectScreenResolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        SectionsOptionsResolver::configure($this, $data);
    }

    /**
     * @param array<mixed> $options
     * @return TCharacter
     */
    public function resolve(array $options = []): array
    {
        /** @var TCharacter $return */
        $return = parent::resolve($options);

        return $return;
    }
}
