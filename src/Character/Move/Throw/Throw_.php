<?php

declare(strict_types=1);

namespace App\Character\Move\Throw;

use App\{
    Character\Move\PropertyEnum,
    Collection\Move\CommentCollection,
    Collection\Move\Throw\BehaviorEnumCollection
};
use Steevanb\PhpCollection\ScalarCollection\StringCollection;

readonly class Throw_
{
    public function __construct(
        public string $name,
        public PropertyEnum $property,
        public Frames $frames,
        public Distances $distances,
        public StringCollection $escapes,
        public int $damage,
        public BehaviorEnumCollection $behaviors,
        public CommentCollection $comments
    ) {
    }
}
