<?php

declare(strict_types=1);

namespace App\Character\Move\Comment;

use App\Collection\Character\Move\CommentCollection;

class CommentsFactory
{
    public static function create(array &$data): CommentCollection
    {
        $return = new CommentCollection();
        foreach ($data as $commentData) {
            $return->add(
                new Comment(
                    $commentData['comment'],
                    TypeEnum::create($commentData['type']),
                    WidthEnum::from($commentData['width'])
                )
            );
        }

        return $return;
    }
}
