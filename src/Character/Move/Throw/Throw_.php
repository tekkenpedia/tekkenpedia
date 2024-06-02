<?php

declare(strict_types=1);

namespace App\Character\Move\Throw;

use App\{
    Character\Move\MoveInterface,
    Character\Move\Throw\Distance\Distances,
    Character\Move\Throw\Frame\Frames,
    Character\Move\Visibility,
    Collection\Character\Move\BehaviorEnumCollection,
    Collection\Character\Move\CommentCollection
};
use Steevanb\PhpCollection\ScalarCollection\StringCollection;

readonly class Throw_ implements MoveInterface
{
    public function __construct(
        public string $id,
        public string $inputs,
        public ?string $situation,
        public string $slug,
        public Visibility $visibility,
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

    public function getVisibility(): Visibility
    {
        return $this->visibility;
    }
}
