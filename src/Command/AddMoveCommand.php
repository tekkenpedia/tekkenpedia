<?php

declare(strict_types=1);

namespace App\Command;

use App\{
    Character\CharacterSlugEnum,
    Character\Move\Attack\AddAttack,
    Character\Move\Attack\DefenseEnum,
    Character\Move\Attack\PropertyEnum,
    Collection\Character\Move\Attack\DefenseEnumCollection,
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
            ->addArgument('defenses', InputArgument::REQUIRED, 'DefenseEnum[]. Example: PUNISH,POWER_CRUSH.')
            ->addArgument('property', InputArgument::REQUIRED, 'Move property')
            ->addArgument('blockFramesMin', InputArgument::REQUIRED, 'Minimum block frames')
            ->addArgument('blockFramesMax', InputArgument::OPTIONAL, 'Maximum block frames (null if not applicable)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string $characterSlug */
        $characterSlug = $input->getArgument('characterSlug');
        /** @var string $property */
        $property = $input->getArgument('property');

        $id = $this->addAttack->add(
            CharacterSlugEnum::from($characterSlug),
            $this->getInputs($input),
            $this->getDefenses($input),
            PropertyEnum::create($property),
            $this->getBlockFramesMin($input),
            $this->getBlockFramesMax($input)
        );

        $output->writeln('Move added: <comment>' . $id . '</comment>.');

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
}
