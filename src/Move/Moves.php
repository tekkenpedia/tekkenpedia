<?php

declare(strict_types=1);

namespace App\Move;

use App\{
    Collection\Move\MoveCollection,
    Collection\Move\SectionCollection,
    OptionsResolver\AllowedTypeEnum
};
use Steevanb\PhpCollection\ScalarCollection\StringCollection;
use Symfony\Component\Finder\Finder;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\{
    Constraints\Positive,
    Validation
};

class Moves
{
    private readonly string $movesPath;

    public function __construct(string $projectDir)
    {
        $this->movesPath = $projectDir . '/data/moves';
    }

    public function getCharactersSlugs(): StringCollection
    {
        $files = (new Finder())
            ->in($this->movesPath)
            ->files()
            ->name('*.json');

        $return = new StringCollection();
        foreach ($files as $file) {
            $return->add($file->getBasename('.json'));
        }

        return $return;
    }

    public function getSections(string $characterSlug): SectionCollection
    {
        $return = new SectionCollection();

        foreach ($this->resolve($this->decodeJson($characterSlug)) as $sectionName => $movesData) {
            $moves = new MoveCollection();
            foreach ($movesData as $moveName => $moveData) {
                $moves->add(
                    new Move(
                        $moveName,
                        PropertyEnum::create($moveData['property']),
                        $moveData['distance'],
                        new Frames(
                            $moveData['frames']['startup'],
                            $moveData['frames']['normal-hit'],
                            $moveData['frames']['counter-hit'],
                            $moveData['frames']['block']
                        ),
                        new Damages($moveData['damages']['normal-hit'], $moveData['damages']['counter-hit']),
                        new Hits(
                            HitEnum::create($moveData['hits']['normal']),
                            HitEnum::create($moveData['hits']['counter'])
                        ),
                        new Steps(
                            StepEnum::create($moveData['steps']['ssl']),
                            StepEnum::create($moveData['steps']['swl']),
                            StepEnum::create($moveData['steps']['ssr']),
                            StepEnum::create($moveData['steps']['swr'])
                        ),
                        new StringCollection($moveData['comments'])
                    )
                );
            }
            $moves->setReadOnly();

            $return->add(new Section($sectionName, $moves));
        }

        return $return->setReadOnly();
    }

    private function decodeJson(string $characterSlug): array
    {
        $jsonPathname = $this->movesPath . '/' . $characterSlug . '.json';
        if (is_readable($jsonPathname) === false) {
            throw new \Exception('File "' . $jsonPathname . '" does not exists or is not readable.');
        }

        $json = file_get_contents($jsonPathname);
        if (is_string($json) === false) {
            throw new \Exception('File "' . $jsonPathname . '" could not be read.');
        }

        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }

    private function resolve(array $moves): array
    {
        $resolver = new OptionsResolver();

        foreach (array_keys($moves) as $sectionName) {
            $resolver
                ->define($sectionName)
                ->required()
                ->default(
                    function (OptionsResolver $sectionResolver) use ($moves, $sectionName): void {
                        $this->configureSectionResolver($sectionResolver, $moves[$sectionName]);
                    }
                )
                ->allowedTypes(AllowedTypeEnum::ARRAY->value);
        }

        return $resolver->resolve($moves);
    }

    private function configureSectionResolver(OptionsResolver $resolver, array $moves): static
    {
        foreach (array_keys($moves) as $moveName) {
            $resolver
                ->define($moveName)
                ->required()
                ->default(
                    function (OptionsResolver $moveResolver) {
                        $this->configureMoveResolver($moveResolver);
                    }
                );
        }

        return $this;
    }

    private function configureMoveResolver(OptionsResolver $resolver): static
    {
        $resolver
            ->define('property')
            ->required()
            ->allowedValues(...PropertyEnum::getValues()->toArray());

        $resolver
            ->define('frames')
            ->required()
            ->default(
                function(OptionsResolver $framesResolver): void {
                    $this->configureFramesResolver($framesResolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $resolver
            ->define('hits')
            ->default(
                function(OptionsResolver $hitsResolver): void {
                    $this->configureHitsResolver($hitsResolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $resolver
            ->define('distance')
            ->required()
            ->allowedTypes(AllowedTypeEnum::INTEGER->value)
            ->allowedValues(
                Validation::createIsValidCallable(new Positive())
            );

        $resolver
            ->define('damages')
            ->required()
            ->default(
                function(OptionsResolver $damagesResolver) {
                    $this->configureDamagesResolver($damagesResolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $resolver
            ->define('steps')
            ->default(
                function(OptionsResolver $stepsResolver): void {
                    $this->configureStepsResolver($stepsResolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $resolver
            ->define('comments')
            ->default([])
            ->allowedTypes(AllowedTypeEnum::ARRAY_OF_STRINGS->value);

        return $this;
    }

    private function configureFramesResolver(OptionsResolver $resolver): static
    {
        $resolver
            ->define('startup')
            ->required()
            ->allowedTypes(AllowedTypeEnum::INTEGER->value);

        $resolver
            ->define('normal-hit')
            ->required()
            ->allowedTypes(AllowedTypeEnum::INTEGER->value);

        $resolver
            ->define('counter-hit')
            ->required()
            ->allowedTypes(AllowedTypeEnum::INTEGER->value);

        $resolver
            ->define('block')
            ->required()
            ->allowedTypes(AllowedTypeEnum::INTEGER->value);

        return $this;
    }

    private function configureHitsResolver(OptionsResolver $resolver): static
    {
        $resolver
            ->define('normal')
            ->default(HitEnum::HIT->name)
            ->allowedValues(...HitEnum::getValues()->toArray());

        $resolver
            ->define('counter')
            ->default(HitEnum::HIT->name)
            ->allowedValues(...HitEnum::getValues()->toArray());

        return $this;
    }

    private function configureStepsResolver(OptionsResolver $resolver): static
    {
        $resolver
            ->define('ssl')
            ->default(StepEnum::IMPOSSIBLE->name)
            ->allowedValues(...StepEnum::getValues()->toArray());

        $resolver
            ->define('swl')
            ->default(StepEnum::IMPOSSIBLE->name)
            ->allowedValues(...StepEnum::getValues()->toArray());

        $resolver
            ->define('ssr')
            ->default(StepEnum::IMPOSSIBLE->name)
            ->allowedValues(...StepEnum::getValues()->toArray());

        $resolver
            ->define('swr')
            ->default(StepEnum::IMPOSSIBLE->name)
            ->allowedValues(...StepEnum::getValues()->toArray());

        return $this;
    }

    private function configureDamagesResolver(OptionsResolver $resolver): static
    {
        $resolver
            ->define('normal-hit')
            ->required()
            ->allowedTypes(AllowedTypeEnum::INTEGER->value)
            ->allowedValues(
                Validation::createIsValidCallable(new Positive())
            );

        $resolver
            ->define('counter-hit')
            ->required()
            ->allowedTypes(AllowedTypeEnum::INTEGER->value)
            ->allowedValues(
                Validation::createIsValidCallable(new Positive())
            );

        return $this;
    }
}
