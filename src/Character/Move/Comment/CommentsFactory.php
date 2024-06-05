<?php

declare(strict_types=1);

namespace App\Character\Move\Comment;

use App\Collection\Character\Move\CommentCollection;

class CommentsFactory
{
    /** @param array<TComment> $comments */
    public static function create(array &$comments): CommentCollection
    {
        $return = new CommentCollection();
        foreach ($comments as $comment) {
            $return->add(
                new Comment(
                    $comment['comment'],
                    TypeEnum::create($comment['type']),
                    WidthEnum::from($comment['width'])
                )
            );
        }

        return $return;
    }
}
