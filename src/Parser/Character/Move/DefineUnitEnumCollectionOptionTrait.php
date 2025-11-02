<?php

declare(strict_types=1);

namespace App\Parser\Character\Move;

use App\{
    Enum\CreateInterface,
    Enum\GetNamesInterface,
    Exception\ShouldNotHappenException,
    OptionsResolver\AllowedTypeEnum,
    OptionsResolver\AllowedValues
};
use Symfony\Component\OptionsResolver\{
    OptionConfigurator,
    OptionsResolver
};
use Steevanb\PhpCollection\ObjectCollection\AbstractObjectCollection;

trait DefineUnitEnumCollectionOptionTrait
{
    abstract public function define(string $option): OptionConfigurator;

    /**
     * @param class-string<CreateInterface&GetNamesInterface> $enumFqcn
     * @param class-string $enumCollectionFqcn
     */
    protected function defineUnitEnumCollectionOption(
        string $name,
        string $enumFqcn,
        string $enumCollectionFqcn
    ): static {
        $this
            ->define($name)
            ->default([])
            ->allowedTypes(AllowedTypeEnum::ARRAY_OF_STRINGS->value)
            ->allowedValues(static fn(array $values) => AllowedValues::isAllowed($values, $enumFqcn::getNames()))
            ->normalize(
                static function (
                    OptionsResolver $optionsResolver,
                    array $values
                ) use (
                    $enumFqcn,
                    $enumCollectionFqcn
                ): AbstractObjectCollection {
                    $return = new $enumCollectionFqcn();
                    if ($return instanceof AbstractObjectCollection === false) {
                        throw new ShouldNotHappenException();
                    }
                    foreach ($values as $value) {
                        $return->add($enumFqcn::create($value));
                    }

                    return $return;
                }
            );

        return $this;
    }
}
