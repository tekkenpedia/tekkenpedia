<?php

declare(strict_types=1);

namespace App\Character\Section\Comment;

readonly class Comment
{
    public function __construct(public string $comment, public TypeEnum $type)
    {
    }
}
