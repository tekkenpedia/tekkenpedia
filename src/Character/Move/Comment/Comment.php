<?php

declare(strict_types=1);

namespace App\Character\Move\Comment;

readonly class Comment
{
    public function __construct(public string $comment, public TypeEnum $type, public WidthEnum $width)
    {
    }
}
