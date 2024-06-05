<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\{
    Character\Move\Behavior\BehaviorEnum,
    Collection\Character\Move\BehaviorEnumCollection,
    Exception\AppException,
    Parser\Character\Move\MoveTypeEnum
};
use Twig\{
    Extension\AbstractExtension,
    TwigFilter,
    TwigFunction
};
use Steevanb\PhpCollection\ScalarCollection\StringCollection;

class MoveExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('getMoveTemplateName', [$this, 'getMoveTemplateName'])
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('move_behaviors_icons', [$this, 'moveBehaviorsIcons'], ['is_safe' => ['html']])
        ];
    }

    public function getMoveTemplateName(MoveTypeEnum $moveType): string
    {
        return match ($moveType) {
            MoveTypeEnum::ATTACK => 'attack',
            MoveTypeEnum::POWER_CRUSH => 'power-crush',
            MoveTypeEnum::THROW => 'throw'
        };
    }

    public function moveBehaviorsIcons(BehaviorEnumCollection $behaviors): string
    {
        $wallBehaviors = BehaviorEnum::getWallBehaviors();
        $wallBehaviorsTitleParts = new StringCollection();

        $floorBehaviors = BehaviorEnum::getFloorBehaviors();
        $floorBehaviorsTitleParts = new StringCollection();

        $returnParts = new StringCollection();

        foreach ($behaviors->toArray() as $behavior) {
            if ($wallBehaviors->contains($behavior)) {
                $this->addWallBehaviorTitlePart($wallBehaviorsTitleParts, $behavior);
            } elseif ($floorBehaviors->contains($behavior)) {
                $this->addFloorBehaviorTitlePart($floorBehaviorsTitleParts, $behavior);
            } elseif ($behavior === BehaviorEnum::KNOCKDOWN) {
                $returnParts->add($this->createItalicIcon('bi-arrow-90deg-down vertical-symmetry', 'Knockdown'));
            } elseif ($behavior === BehaviorEnum::AIR) {
                $returnParts->add($this->createItalicIcon('bi-arrow-up-right', 'Air'));
            } elseif ($behavior === BehaviorEnum::DELETE_RECOVERABLE_LIFE_BAR) {
                $returnParts->add($this->createPngIcon('delete-recoverable-life-bar', 'Delete recoverable life bar'));
            } elseif ($behavior === BehaviorEnum::HEAT_BURST) {
                $returnParts->add($this->createPngIcon('heat-engager', 'Heat burst'));
            } elseif ($behavior === BehaviorEnum::HEAT_ENGAGER) {
                $returnParts->add($this->createPngIcon('heat-engager', 'Heat engager'));
            } elseif ($behavior === BehaviorEnum::OPPONENT_CROUCH) {
                $returnParts->add($this->createPngIcon('opponent-crouch', 'Opponent is crouch'));
            } else {
                throw new AppException('Unknown behavior ' . $behavior->name . '.');
            }
        }

        if ($wallBehaviorsTitleParts->count() > 0) {
            $returnParts->add($this->createItalicIcon('bi-box-arrow-in-right', $wallBehaviorsTitleParts));
        }

        if ($floorBehaviorsTitleParts->count() > 0) {
            $returnParts->add($this->createItalicIcon('bi-download', $floorBehaviorsTitleParts));
        }

        return implode(' ', $returnParts->toArray());
    }

    private function addWallBehaviorTitlePart(StringCollection $wallBehaviorsTitleParts, BehaviorEnum $behavior): static
    {
        switch ($behavior) {
            case BehaviorEnum::WALL_SPLAT:
                $wallBehaviorsTitleParts->add('wall splat');
                break;
            case BehaviorEnum::WALL_BREAK:
                $wallBehaviorsTitleParts->add('wall break');
                break;
            case BehaviorEnum::HARD_WALL_BREAK:
                $wallBehaviorsTitleParts->add('hard wall break');
                break;
            case BehaviorEnum::WALL_BOUND:
                $wallBehaviorsTitleParts->add('wall bound');
                break;
            case BehaviorEnum::WALL_BLAST:
                $wallBehaviorsTitleParts->add('wall blast');
                break;
            default:
                throw new AppException('Unknown wall behavior ' . $behavior->name . '.');
        }

        return $this;
    }

    private function addFloorBehaviorTitlePart(
        StringCollection $floorBehaviorsTitleParts,
        BehaviorEnum $behavior
    ): static {
        switch ($behavior) {
            case BehaviorEnum::FLOOR_BREAK:
                $floorBehaviorsTitleParts->add('floor break');
                break;
            case BehaviorEnum::FLOOR_BLAST:
                $floorBehaviorsTitleParts->add('floor blast');
                break;
            default:
                throw new AppException('Unknown floor behavior ' . $behavior->name . '.');
        }

        return $this;
    }

    private function createItalicIcon(string $icon, StringCollection|string $title): string
    {
        $titleHtml = is_string($title) ? $title : ucfirst(implode(', ', $title->toArray()));

        return '<i class="icon-move-behavior bi ' . $icon . '" title="' . $titleHtml . '"></i>';
    }

    private function createPngIcon(string $icon, string $title): string
    {
        return
            '<img class="icon-move-behavior" src="../../../images/behaviors/' . $icon . '.png" title="' . $title . '">';
    }
}
