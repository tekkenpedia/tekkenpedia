<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\{
    Move\HitEnum,
    Move\Move,
    Move\PropertyEnum
};
use Twig\{
    Extension\AbstractExtension,
    TwigFilter
};

class FormatExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('format_frame', [$this, 'formatFrame']),
            new TwigFilter('format_normal_hit', [$this, 'formatNormalHit']),
            new TwigFilter('format_counter_hit', [$this, 'formatCounterHit']),
            new TwigFilter('format_block', [$this, 'formatBlock']),
            new TwigFilter('frame_bg_class', [$this, 'frameBgClass']),
            new TwigFilter('format_property', [$this, 'formatProperty'])
        ];
    }

    public function formatNormalHit(Move $move): string
    {
        return $this->formatAttack($move->hits->normal, $move->frames->normalHit);
    }

    public function formatCounterHit(Move $move): string
    {
        return $this->formatAttack($move->hits->counter, $move->frames->counterHit);
    }

    public function formatBlock(Move $move): string
    {
        return $this->formatAttack(HitEnum::HIT, $move->frames->block);
    }

    public function frameBgClass(int $frame): string
    {
        if ($frame === 0) {
            $return = 'bg-warning';
        } elseif ($frame < 0) {
            $return = 'bg-success';
        } else {
            $return = 'bg-danger';
        }

        return $return;
    }

    public function formatProperty(PropertyEnum $property): string
    {
        return ucfirst(strtolower($property->name));
    }

    public function formatFrame(int $frame, bool $absolute = false): string
    {
        if ($absolute || $frame <= 0) {
            $return = (string) $frame;
        } else {
            $return = '+' . $frame;
        }

        return $return;
    }

    private function formatAttack(HitEnum $hit, int $frame): string
    {
        return match ($hit) {
            HitEnum::KNOCKDOWN => 'KD',
            HitEnum::AIR => 'AIR',
            HitEnum::HIT => $this->formatFrame($frame)
        };
    }
}
