<?php

declare(strict_types=1);

namespace App\Character\Move\Throw;

use App\{
    Character\Move\MoveInterface,
    Character\Move\Throw\Frames\Frames,
    Collection\Character\Move\BehaviorEnumCollection,
    Collection\Character\Move\CommentCollection};
use Steevanb\PhpCollection\ScalarCollection\StringCollection;

readonly class Throw_ implements MoveInterface
{
    public function __construct(
        public string $name,
        public string $slug,
        public PropertyEnum $property,
        public Frames $frames,
        public Distances $distances,
        public StringCollection $escapes,
        public Damages $damages,
        public BehaviorEnumCollection $behaviors,
        public CommentCollection $comments
    ) {
    }

    public function getSlug(): string
    {
        return $this->slug;
    }
}
