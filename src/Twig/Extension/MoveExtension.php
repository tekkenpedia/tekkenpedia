<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\{
    Character\Character,
    Character\Move\Attack\DefenseEnum,
    Character\Move\Behavior\BehaviorEnum,
    Character\Move\FramesBlockInterface,
    Character\Move\MoveInterface,
    Character\Move\UsedEnum,
    Character\Move\Visibility,
    Collection\Character\Move\Attack\DefenseEnumCollection,
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
    public function __construct(private readonly string $projectDir, private readonly bool $copyMoveId)
    {
    }

    /** @return TwigFunction[] */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('getMoveTemplateName', $this->getMoveTemplateName(...)),
            new TwigFunction('moveHasVideo', $this->moveHasVideo(...)),
            new TwigFunction('getMoveVideoPath', $this->getMoveVideoPath(...)),
            new TwigFunction('copyMoveId', $this->copyMoveId(...)),
            new TwigFunction('getMoveDefenses', $this->getMoveDefenses(...))
        ];
    }

    /** @return TwigFilter[] */
    public function getFilters(): array
    {
        return [
            new TwigFilter('move_behaviors_icons', $this->moveBehaviorsIcons(...), ['is_safe' => ['html']]),
            new TwigFilter('move_visible', $this->moveVisible(...), ['is_safe' => ['html']]),
            new TwigFilter('move_defense', $this->moveDefense(...), ['is_safe' => ['html']]),
            new TwigFilter('move_used', $this->moveUsed(...), ['is_safe' => ['html']]),
            new TwigFilter('move_type', $this->moveType(...), ['is_safe' => ['html']])
        ];
    }

    public function getMoveDefenses(): DefenseEnumCollection
    {
        return DefenseEnumCollection::createAll();
    }

    public function getMoveVideoPath(Character $character, MoveInterface $move, string $pageType): string
    {
        $path = 'images/characters/' . $character->slug . '/' . $pageType . '/';

        // TODO : mettre toutes les images avec le slug
        return $path . (($pageType === 'punish') ? $move->getId() : $move->getSlug()) . '.gif';
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

    public function copyMoveId(): bool
    {
        return $this->copyMoveId;
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
        $return = new StringCollection();
        foreach ($move->getDefenses()->toArray() as $defense) {
            $return->add(
                match ($defense) {
                    DefenseEnum::PUNISH => 'Punish',
                    DefenseEnum::SSL => 'SSL',
                    DefenseEnum::SWL => 'SWL',
                    DefenseEnum::SSR => 'SSR',
                    DefenseEnum::SWR => 'SWR',
                    DefenseEnum::DUCK => 'Duck',
                    DefenseEnum::REVERSAL => 'Reversal',
                    DefenseEnum::POWER_CRUSH => 'Power cr.',
                    DefenseEnum::RAGE_ART => 'Rage art',
                    DefenseEnum::LOW_PARRY => 'Low parry',
                    DefenseEnum::THROW => 'Throw',
                    DefenseEnum::INTERRUPT_10F => 'Break 10F',
                    DefenseEnum::INTERRUPT_11F => 'Break 11F',
                    DefenseEnum::INTERRUPT_12F => 'Break 12F',
                    DefenseEnum::INTERRUPT_13F => 'Break 13F',
                    DefenseEnum::INTERRUPT_14F => 'Break 14F',
                    DefenseEnum::INTERRUPT_15F => 'Break 15F'
                }
            );
        }

        if ($return->count() === 0) {
            return '';
        }

        $htmlRows = [
            '<tr class="separator"><td class="col-12" colspan="4">Defense'
            . ($return->count() > 1 ? 's' : null)
            . '</td></tr>'
        ];

        // Transformer chaque label en <td> et grouper par 4 pour entourer par <tr>
        $cells = array_map(fn($label) => '<td class="col-3">' . $label . '</td>', $return->toArray());
        $rows = array_chunk($cells, 4);
        $htmlRows = array_merge($htmlRows, array_map(fn($row) => '<tr>' . implode('', $row) . '</tr>', $rows));

        return implode('', $htmlRows);
    }

    public function moveUsed(?UsedEnum $used): ?string
    {
        if ($used === null) {
            return null;
        }

        $stars = match ($used) {
            UsedEnum::RARELY => 1,
            UsedEnum::SITUATIONALLY => 2,
            UsedEnum::FREQUENTLY => 3
        };

        $starsHtmlParts = new StringCollection();
        for ($star = 1; $star <= 3; $star++) {
            $starsHtmlParts->add('<i class="bi bi-star' . ($star <= $stars ? '-fill' : null) . '"></i>');
        }

        return implode(' ', $starsHtmlParts->toArray());
    }

    public function moveType(MoveTypeEnum $type): string
    {
        return match ($type) {
            MoveTypeEnum::ATTACK => 'Attack',
            MoveTypeEnum::POWER_CRUSH => 'Power cr.',
            MoveTypeEnum::THROW => 'Throw'
        };
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
