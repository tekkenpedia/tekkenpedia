<?php

declare(strict_types=1);

namespace App\Collection\Move;

use App\Move\Comment\Comment;
use Steevanb\PhpCollection\ObjectCollection\AbstractObjectCollection;

class CommentCollection extends AbstractObjectCollection
{
    public function __construct()
    {
        parent::__construct(Comment::class);
    }

    public function add(Comment $move): static
    {
        return $this->doAdd($move);
    }
}
