<?php

declare(strict_types=1);

namespace App\Command;

use App\{
    Character\CharacterSlugEnum,
    Character\Move\Attack\AddAttack,
    Character\Move\Attack\DefenseEnum,
    Character\Move\Attack\PropertyEnum,
    Character\Move\UsedEnum,
    Collection\Character\Move\Attack\DefenseEnumCollection,
    Collection\Character\Move\Attack\PropertyEnumCollection,
    Exception\AppException
};
use Symfony\Component\Console\{
    Command\Command,
    Input\InputArgument,
    Input\InputInterface,
    Output\OutputInterface
};

class AddMoveCommand extends Command
{
    public static function getDefaultName(): ?string
    {
        return 'move:add';
    }

    public static function getDefaultDescription(): ?string
    {
        return 'Add a move';
    }

    public function __construct(private readonly AddAttack $addAttack)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('characterSlug', InputArgument::REQUIRED, 'Character slug')
            ->addArgument('inputs', InputArgument::REQUIRED, 'Move inputs')
            ->addArgument('used', InputArgument::REQUIRED, 'Used. Allowed values: RARELY, SITUATIONALLY, FREQUENTLY.')
            ->addArgument('defenses', InputArgument::REQUIRED, 'DefenseEnum[]. Examples: PUNISH, POWER_CRUSH.')
            ->addArgument('properties', InputArgument::REQUIRED, 'PropertyEnum[]. Examples: HIGH, MIDDLE.')
            ->addArgument('blockFramesMin', InputArgument::REQUIRED, 'Minimum block frames')
            ->addArgument('blockFramesMax', InputArgument::OPTIONAL, 'Maximum block frames (null if not applicable)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string $characterSlug */
        $characterSlug = $input->getArgument('characterSlug');

        $id = $this->addAttack->add(
            CharacterSlugEnum::from($characterSlug),
            $this->getInputs($input),
            $this->getUsed($input),
            $this->getDefenses($input),
            $this->getProperties($input),
            $this->getBlockFramesMin($input),
            $this->getBlockFramesMax($input)
        );

        $output->writeln('Move id: ' . $id);

        return static::SUCCESS;
    }

    private function getBlockFramesMin(InputInterface $input): int
    {
        $return = $input->getArgument('blockFramesMin');
        if (is_numeric($return) === false) {
            throw new AppException('blockFramesMin should be a number.');
        }

        return (int) $return;
    }

    private function getBlockFramesMax(InputInterface $input): ?int
    {
        $return = $input->getArgument('blockFramesMax');
        if (is_string($return) && is_numeric($return) === false) {
            throw new AppException('blockFramesMax should be a number.');
        }

        return is_string($return) ? (int) $return : null;
    }

    private function getInputs(InputInterface $input): string
    {
        /** @var string $inputs */
        $inputs = $input->getArgument('inputs');

        return str_replace('%20', ' ', $inputs);
    }

    private function getUsed(InputInterface $input): UsedEnum
    {
        /** @var string $used */
        $used = $input->getArgument('used');

        return UsedEnum::create($used);
    }

    private function getDefenses(InputInterface $input): DefenseEnumCollection
    {
        $return = new DefenseEnumCollection();
        /** @var string $defenses */
        $defenses = $input->getArgument('defenses');

        foreach (explode(',', $defenses) as $defense) {
            $return->add(DefenseEnum::create($defense));
        }

        return $return;
    }

    private function getProperties(InputInterface $input): PropertyEnumCollection
    {
        $return = $input->getArgument('properties');
        if (is_string($return) === false) {
            throw new AppException('properties should be a string.');
        }

        return new PropertyEnumCollection(
            array_map(
                static fn(string $property) => PropertyEnum::create($property),
                explode(',', $return)
            )
        );
    }
}
