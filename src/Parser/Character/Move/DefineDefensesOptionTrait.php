<?php

declare(strict_types=1);

namespace App\Parser\Character\Move;

use App\{
    Character\Move\Attack\DefenseEnum,
    Collection\Character\Move\Attack\DefenseEnumCollection,
    OptionsResolver\AllowedTypeEnum,
    OptionsResolver\AllowedValues
};
use Symfony\Component\OptionsResolver\{
    OptionConfigurator,
    OptionsResolver
};

trait DefineDefensesOptionTrait
{
    abstract public function define(string $option): OptionConfigurator;

    protected function defineDefensesOption(): static
    {
        $this
            ->define('defenses')
            ->default([])
            ->allowedTypes(AllowedTypeEnum::ARRAY_OF_STRINGS->value)
            ->allowedValues(static fn(array $values) => AllowedValues::isAllowed($values, DefenseEnum::getNames()))
            ->normalize(
                static function (OptionsResolver $optionsResolver, array $values): DefenseEnumCollection {
                    $return = new DefenseEnumCollection();
                    foreach ($values as $value) {
                        $return->add(DefenseEnum::create($value));
                    }

                    return $return;
                }
            );

        return $this;
    }
}
