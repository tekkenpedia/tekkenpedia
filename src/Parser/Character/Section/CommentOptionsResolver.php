<?php

declare(strict_types=1);

namespace App\Parser\Character\Section;

use App\{
    Callable\Callable_,
    Character\Section\Comment\TypeEnum,
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
            ->default(TypeEnum::DEFENSE->name)
            ->allowedValues(...TypeEnum::getNames()->toArray());

        $comments = array_map(Callable_::create($resolver, 'resolve'), $comments);

        return true;
    }
}
