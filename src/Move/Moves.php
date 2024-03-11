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

        foreach ($this->resolve($this->decodeJson($characterSlug)) as $sectionName => $sectionData) {
            $return->add($this->createSection($sectionName, $sectionData));
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

    private function resolve(array $data): array
    {
        $resolver = new OptionsResolver();

        foreach (array_keys($data) as $sectionName) {
            $resolver
                ->define($sectionName)
                ->default(
                    function (OptionsResolver $sectionResolver) use ($data, $sectionName): void {
                        $this->configureSectionResolver($sectionResolver, $data[$sectionName]);
                    }
                )
                ->allowedTypes(AllowedTypeEnum::ARRAY->value);
        }

        return $resolver->resolve($data);
    }

    private function configureSectionResolver(OptionsResolver $resolver, array $section): static
    {
        $resolver
            ->define('moves')
            ->default(
                function (OptionsResolver $optionsResolver) use ($section): void {
                    foreach (array_keys($section['moves'] ?? []) as $moveName) {
                        $optionsResolver
                            ->define($moveName)
                            ->default(
                                function(OptionsResolver $moveResolver): void {
                                    $this->configureMoveResolver($moveResolver);
                                }
                            )
                            ->allowedTypes(AllowedTypeEnum::ARRAY->value);
                    }
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $resolver
            ->define('sections')
            ->default(
                function (OptionsResolver $optionsResolver) use ($section): void {
                    foreach (array_keys($section['sections'] ?? []) as $sectionName) {
                        $optionsResolver
                            ->define($sectionName)
                            ->default(
                                function(OptionsResolver $sectionResolver) use ($section, $sectionName): void {
                                    $this->configureSectionResolver($sectionResolver, $section['sections'][$sectionName]);
                                }
                            )
                            ->allowedTypes(AllowedTypeEnum::ARRAY->value);
                    }
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

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

    private function createMove(string $name, array $data): Move
    {
        $comments = new CommentCollection();
        foreach ($data['comments'] as $commentData) {
            $comments->add(
                new Comment(
                    $commentData['comment'],
                    TypeEnum::create($commentData['type']),
                    WidthEnum::from($commentData['width'])
                )
            );
        }

        return new Move(
            $name,
            PropertyEnum::create($data['property']),
            $data['distance'],
            new Frames(
                $data['frames']['startup'],
                $data['frames']['normal-hit'],
                $data['frames']['counter-hit'],
                $data['frames']['block']
            ),
            new Damages($data['damages']['normal-hit'], $data['damages']['counter-hit']),
            new Hits(
                HitEnum::create($data['hits']['normal']),
                HitEnum::create($data['hits']['counter'])
            ),
            new Steps(
                StepEnum::create($data['steps']['ssl']),
                StepEnum::create($data['steps']['swl']),
                StepEnum::create($data['steps']['ssr']),
                StepEnum::create($data['steps']['swr'])
            ),
            $comments
        );
    }

    private function createSection(string $name, array $data): Section
    {
        $moves = new MoveCollection();
        foreach ($data['moves'] as $moveName => $moveData) {
            $moves->add($this->createMove($moveName, $moveData));
        }
        $moves->setReadOnly();

        $sections = new SectionCollection();
        foreach ($data['sections'] as $subSectionName => $subSectionData) {
            $sections->add($this->createSection($subSectionName, $subSectionData));
        }
        $sections->setReadOnly();

        return new Section($name, $moves, $sections);
    }
}
