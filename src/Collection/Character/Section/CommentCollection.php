<?php

declare(strict_types=1);

namespace App\Collection\Character\Section;

use App\Character\Section\Comment\Comment;
use Steevanb\PhpCollection\ObjectCollection\AbstractObjectCollection;

/** @extends AbstractObjectCollection<Comment> */
class CommentCollection extends AbstractObjectCollection
{
    public static function getValueFqcn(): string
    {
        return Comment::class;
    }
}
