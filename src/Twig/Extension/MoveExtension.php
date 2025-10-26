<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\{
    Character\Character,
    Character\Move\Attack\Attack,
    Character\Move\Attack\DefenseEnum,
    Character\Move\Behavior\BehaviorEnum,
    Character\Move\MoveInterface,
    Character\Move\Visibility,
    Collection\Character\Move\BehaviorEnumCollection,
    Exception\AppException,
    Parser\Character\Move\MoveTypeEnum
};
use Steevanb\PhpCollection\ScalarCollection\StringCollection;
use Twig\{
    Extension\AbstractExtension,
    TwigFilter,
    TwigFunction
};

class MoveExtension extends AbstractExtension
{
    public function __construct(private readonly string $projectDir, private readonly FormatExtension $formatExtension)
    {
    }

    /** @return TwigFunction[] */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('getMoveTemplateName', $this->getMoveTemplateName(...)),
            new TwigFunction('moveHasVideo', $this->moveHasVideo(...)),
            new TwigFunction('getMoveVideoPath', $this->getMoveVideoPath(...)),
        ];
    }

    /** @return TwigFilter[] */
    public function getFilters(): array
    {
        return [
            new TwigFilter('move_behaviors_icons', $this->moveBehaviorsIcons(...), ['is_safe' => ['html']]),
            new TwigFilter('move_visible', $this->moveVisible(...), ['is_safe' => ['html']]),
            new TwigFilter('move_defense', $this->moveDefense(...), ['is_safe' => ['html']])
        ];
    }

    public function getMoveVideoPath(Character $character, MoveInterface $move, string $pageType): string
    {
        return 'images/characters/' . $character->slug . '/' . $pageType . '/' . $move->getSlug() . '.gif';
    }

    public function moveHasVideo(Character $character, MoveInterface $move, string $pageType): bool
    {
        return file_exists($this->projectDir . '/docs/' . $this->getMoveVideoPath($character, $move, $pageType));
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

    public function moveVisible(Visibility $visibility, string $pageType): bool
    {
        return match ($pageType) {
            'defense' => $visibility->defense,
            'punish' => $visibility->punish,
            default => throw new AppException('Unknown page type ' . $pageType . '.')
        };
    }

    public function moveDefense(MoveInterface $move): string
    {
        if ($move instanceof Attack === false) {
            throw new AppException('Only ' . Attack::class . ' move type can have a defense for now.');
        }

        $return = new StringCollection();
        foreach ($move->defenses->toArray() as $defense) {
            $return->add(
                match ($defense) {
                    DefenseEnum::PUNISH => (string) $this->formatExtension->formatMinMaxFrames($move->frames->block),
                    DefenseEnum::SSL => 'SSL',
                    DefenseEnum::SWL => 'SWL',
                    DefenseEnum::SSR => 'SSR',
                    DefenseEnum::SWR => 'SWR',
                    DefenseEnum::DUCK => 'Duck',
                    DefenseEnum::REVERSAL => 'Reversal',
                    DefenseEnum::POWER_CRUSH => 'Power crush',
                    DefenseEnum::LOW_PARRY => 'Low parry'
                }
            );
        }

        if ($return->count() === 0) {
            throw new AppException('Move ' . $move->id . ' has no defense.');
        }

        return implode(', ', $return->toArray());
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
