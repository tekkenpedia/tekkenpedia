<?php

declare(strict_types=1);

namespace App\Move;

use App\{
    Collection\Move\CommentCollection,
    Collection\Move\MoveCollection,
    Collection\Move\SectionCollection,
    Collection\Move\Throw\PropertyEnumCollection,
    Collection\Move\Throw\ThrowCollection,
    Move\Comment\Comment,
    Move\Comment\TypeEnum,
    Move\Comment\WidthEnum,
    Move\Throw\Distances,
    Move\Throw\Frames as ThrowFrames,
    Move\Throw\PropertyEnum as ThrowPropertyEnum,
    Move\Throw\Throw_,
    OptionsResolver\AllowedTypeEnum};
use Steevanb\PhpCollection\ScalarCollection\IntegerCollection;
use Steevanb\PhpCollection\ScalarCollection\StringCollection;
use Symfony\Component\Finder\Finder;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\{
    Constraints\Positive,
    Validation};

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
            ->define('throws')
            ->default(
                function (OptionsResolver $optionsResolver) use ($section): void {
                    foreach (array_keys($section['throws'] ?? []) as $throwName) {
                        $optionsResolver
                            ->define($throwName)
                            ->default(
                                function(OptionsResolver $moveResolver): void {
                                    $this->configureThrowResolver($moveResolver);
                                }
                            )
                            ->allowedTypes(AllowedTypeEnum::ARRAY->value);
                    }
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

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

    private function configureThrowResolver(OptionsResolver $resolver): static
    {
        $resolver
            ->define('frames')
            ->required()
            ->default(
                function(OptionsResolver $framesResolver): void {
                    $this->configureThrowFramesResolver($framesResolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $resolver
            ->define('distances')
            ->required()
            ->default(
                function(OptionsResolver $framesResolver): void {
                    $this->configureThrowDistancesResolver($framesResolver);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $resolver
            ->define('escapes')
            ->default([])
            ->allowedTypes(AllowedTypeEnum::ARRAY_OF_INTEGERS->value);

        $resolver
            ->define('damage')
            ->required()
            ->allowedTypes(AllowedTypeEnum::INTEGER->value);

        $resolver
            ->define('properties')
            ->default([])
            ->allowedValues(
                function(array $properties): bool {
                    $allowedProperties = ThrowPropertyEnum::getNames();
                    foreach ($properties as $property) {
                        if (in_array($property, $allowedProperties->toArray(), true) === false) {
                            return false;
                        }
                    }

                    return true;
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY_OF_STRINGS->value);

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
                    $this->configureMoveFramesResolver($framesResolver);
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

    private function configureThrowDistancesResolver(OptionsResolver $resolver): static
    {
        $resolver
            ->define('startup')
            ->required()
            ->allowedTypes(AllowedTypeEnum::INTEGER->value);

        $resolver
            ->define('after-escape')
            ->default(null)
            ->allowedTypes(AllowedTypeEnum::NULL->value, AllowedTypeEnum::INTEGER->value);

        return $this;
    }

    private function configureThrowFramesResolver(OptionsResolver $resolver): static
    {
        $resolver
            ->define('startup')
            ->required()
            ->allowedTypes(AllowedTypeEnum::INTEGER->value);

        $resolver
            ->define('escape')
            ->default(null)
            ->allowedTypes(AllowedTypeEnum::NULL->value, AllowedTypeEnum::INTEGER->value);

        $resolver
            ->define('after-escape')
            ->default(null)
            ->allowedTypes(AllowedTypeEnum::NULL->value, AllowedTypeEnum::INTEGER->value);

        return $this;
    }

    private function configureMoveFramesResolver(OptionsResolver $resolver): static
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

    private function createThrow(string $name, array $data): Throw_
    {
        $properties = new PropertyEnumCollection();
        foreach ($data['properties'] as $property) {
            $properties->add(ThrowPropertyEnum::create($property));
        }

        return new Throw_(
            $name,
            new ThrowFrames(
                $data['frames']['startup'],
                $data['frames']['escape'],
                $data['frames']['after-escape']
            ),
            new Distances(
                $data['distances']['startup'],
                $data['distances']['after-escape'],
            ),
            new IntegerCollection($data['escapes']),
            $data['damage'],
            $properties,
            $this->createComments($data)
        );
    }

    private function createMove(string $name, array $data): Move
    {
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
            $this->createComments($data)
        );
    }

    private function createComments(array $data): CommentCollection
    {
        $return = new CommentCollection();
        foreach ($data['comments'] as $commentData) {
            $return->add(
                new Comment(
                    $commentData['comment'],
                    TypeEnum::create($commentData['type']),
                    WidthEnum::from($commentData['width'])
                )
            );
        }

        return $return;
    }

    private function createSection(string $name, array $data): Section
    {
        $throws = new ThrowCollection();
        foreach ($data['throws'] as $throwName => $throwData) {
            $throws->add($this->createThrow($throwName, $throwData));
        }
        $throws->setReadOnly();

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

        return new Section($name, $throws, $moves, $sections);
    }
}
