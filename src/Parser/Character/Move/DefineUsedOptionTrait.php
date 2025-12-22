<?php

declare(strict_types=1);

namespace App\Parser\Character\Move;

use App\Character\Move\UsedEnum;
use App\OptionsResolver\AllowedTypeEnum;
use Symfony\Component\OptionsResolver\OptionConfigurator;

trait DefineUsedOptionTrait
{
    abstract public function define(string $option): OptionConfigurator;

    private function defineUsedOption(): static
    {
        $this
            ->define('used')
            ->default(null)
            ->allowedTypes(AllowedTypeEnum::STRING->value, AllowedTypeEnum::NULL->value)
            ->allowedValues(null, ...UsedEnum::getNames()->toArray());

        return $this;
    }
}
