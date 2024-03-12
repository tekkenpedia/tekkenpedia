<?php

declare(strict_types=1);

namespace App\Move\Throw;

use App\{
    Collection\Move\CommentCollection,
    Collection\Move\Throw\PropertyEnumCollection
};
use Steevanb\PhpCollection\ScalarCollection\IntegerCollection;

readonly class Throw_
{
    public function __construct(
        public string $name,
        public Frames $frames,
        public Distances $distances,
        public IntegerCollection $escapes,
        public int $damage,
        public PropertyEnumCollection $properties,
        public CommentCollection $comments
    ) {
    }
}
