<?php

declare(strict_types=1);

namespace App\Character\Section\Comment;

use App\Collection\Character\Section\CommentCollection;

class CommentsFactory
{
    /** @param array<TSectionComment> $comments */
    public static function create(array &$comments): CommentCollection
    {
        $return = new CommentCollection();
        foreach ($comments as $comment) {
            $return->add(
                new Comment(
                    $comment['comment'],
                    TypeEnum::create($comment['type'])
                )
            );
        }

        return $return;
    }
}
