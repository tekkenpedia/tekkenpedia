<?php

declare(strict_types=1);

namespace App\Parser\Character;

use App\{
    Callable\Callable_,
    Character\Move\Comment\TypeEnum,
    Character\Move\Comment\WidthEnum,
    OptionsResolver\AllowedTypeEnum
};
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentOptionsResolver
{
    /** @param array<mixed> $comments */
    public static function resolve(array &$comments): true
    {
        $resolver = new OptionsResolver();

        $resolver
            ->define('comment')
            ->required()
            ->allowedTypes(AllowedTypeEnum::STRING->value);

        $resolver
            ->define('type')
            ->default(TypeEnum::NORMAL->name)
            ->allowedValues(...TypeEnum::getNames()->toArray());

        $resolver
            ->define('width')
            ->default(WidthEnum::FOUR->value)
            ->allowedValues(...WidthEnum::getValues()->toArray());

        $comments = array_map(Callable_::create($resolver, 'resolve'), $comments);

        return true;
    }
}
