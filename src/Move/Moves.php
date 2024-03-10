<?php

declare(strict_types=1);

namespace App\Move;

use App\{
    Collection\Move\CommentCollection,
    Collection\Move\MoveCollection,
    Collection\Move\SectionCollection,
    Move\Comment\Comment,
    Move\Comment\TypeEnum,
    Move\Comment\WidthEnum,
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
                $comments = new CommentCollection();
                foreach ($moveData['comments'] as $commentData) {
                    $comments->add(
                        new Comment(
                            $commentData['comment'],
                            TypeEnum::create($commentData['type']),
                            WidthEnum::from($commentData['width'])
                        )
                    );
                }

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
                        $comments
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
            ->allowedValues(...PropertyEnum::getNames()->toArray());

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
            ->allowedValues(
                function(&$comments): bool {
                    return $this->configureCommentsResolver($comments);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

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
            ->allowedValues(...HitEnum::getNames()->toArray());

        $resolver
            ->define('counter')
            ->default(HitEnum::HIT->name)
            ->allowedValues(...HitEnum::getNames()->toArray());

        return $this;
    }

    private function configureStepsResolver(OptionsResolver $resolver): static
    {
        $resolver
            ->define('ssl')
            ->default(StepEnum::IMPOSSIBLE->name)
            ->allowedValues(...StepEnum::getNames()->toArray());

        $resolver
            ->define('swl')
            ->default(StepEnum::IMPOSSIBLE->name)
            ->allowedValues(...StepEnum::getNames()->toArray());

        $resolver
            ->define('ssr')
            ->default(StepEnum::IMPOSSIBLE->name)
            ->allowedValues(...StepEnum::getNames()->toArray());

        $resolver
            ->define('swr')
            ->default(StepEnum::IMPOSSIBLE->name)
            ->allowedValues(...StepEnum::getNames()->toArray());

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

    private function configureCommentsResolver(array &$comments): bool
    {
        $resolver = new OptionsResolver();

        $resolver
            ->define('comment')
            ->required()
            ->allowedTypes(AllowedTypeEnum::STRING->value);

        $resolver
            ->define('type')
            ->default(TypeEnum::NORMAL->name)
            ->allowedValues(...TypeEnum::getNames()->toArray());

        $resolver
            ->define('width')
            ->default(WidthEnum::FOUR->value)
            ->allowedValues(...WidthEnum::getValues()->toArray());

        $comments = array_map([$resolver, 'resolve'], $comments);

        return true;
    }
}
