<?php

declare(strict_types=1);

namespace App\Parser\Character;

use App\{
    Character\Move\Comment\TypeEnum,
    Character\Move\Comment\WidthEnum,
    Character\Move\HitEnum,
    Character\Move\PropertyEnum,
    Character\Move\Step\StepEnum,
    Character\Move\Throw\BehaviorEnum,
    OptionsResolver\AllowedTypeEnum
};
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\{
    Constraints\Positive,
    Validation
};

class JsonParser
{
    public function getData(string $pathname): array
    {
        $resolver = new OptionsResolver();

        $resolver
            ->define('name')
            ->required()
            ->allowedTypes(AllowedTypeEnum::STRING->value);

        $data = $this->decodeJson($pathname);
        $this->configureMoves($resolver, $data);

        return $resolver->resolve($data);
    }

    private function decodeJson(string $pathname): array
    {
        if (is_readable($pathname) === false) {
            throw new \Exception('File "' . $pathname . '" does not exists or is not readable.');
        }

        $json = file_get_contents($pathname);
        if (is_string($json) === false) {
            throw new \Exception('File "' . $pathname . '" could not be read.');
        }

        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }

    private function configureMoves(OptionsResolver $resolver, array &$data): array
    {
        $resolver
            ->define('moves')
            ->default(
                function(OptionsResolver $resolver) use (&$data): void {
                    foreach (array_keys($data['moves'] ?? []) as $sectionName) {
                        $resolver
                            ->define($sectionName)
                            ->default(
                                function (OptionsResolver $sectionResolver) use ($data, $sectionName): void {
                                    $this->configureSectionResolver($sectionResolver, $data['moves'][$sectionName]);
                                }
                            )
                            ->allowedTypes(AllowedTypeEnum::ARRAY->value);
                    }
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        return $resolver->resolve($data);
    }

    private function configureSectionResolver(OptionsResolver $resolver, array $section): static
    {
        $resolver
            ->define('throws')
            ->default(
                function (OptionsResolver $resolver) use ($section): void {
                    foreach (array_keys($section['throws'] ?? []) as $throwName) {
                        $resolver
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
                function (OptionsResolver $resolver) use ($section): void {
                    foreach (array_keys($section['moves'] ?? []) as $moveName) {
                        $resolver
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
                function (OptionsResolver $resolver) use ($section): void {
                    foreach (array_keys($section['sections'] ?? []) as $sectionName) {
                        $resolver
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
            ->define('slug')
            ->required()
            ->allowedTypes(AllowedTypeEnum::STRING->value);

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
            ->allowedTypes(AllowedTypeEnum::ARRAY_OF_STRINGS->value);

        $resolver
            ->define('damage')
            ->required()
            ->allowedTypes(AllowedTypeEnum::INTEGER->value);

        $resolver
            ->define('property')
            ->default(PropertyEnum::HIGH->name)
            ->allowedValues(...PropertyEnum::getNames()->toArray());

        $resolver
            ->define('comments')
            ->default([])
            ->allowedValues(
                function(&$comments): bool {
                    return $this->configureCommentsResolver($comments);
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY->value);

        $this->configureBehaviorsResolver($resolver);

        return $this;
    }

    private function configureMoveResolver(OptionsResolver $resolver): static
    {
        $resolver
            ->define('slug')
            ->required()
            ->allowedTypes(AllowedTypeEnum::STRING->value);

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

        $this->configureBehaviorsResolver($resolver);

        return $this;
    }

    private function configureThrowDistancesResolver(OptionsResolver $resolver): static
    {
        $resolver
            ->define('startup')
            ->required()
            ->allowedTypes(AllowedTypeEnum::INTEGER->value);

        $resolver
            ->define('hit')
            ->required()
            ->allowedTypes(AllowedTypeEnum::INTEGER->value);

        $resolver
            ->define('escape')
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
            ->define('hit')
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

    private function configureBehaviorsResolver(OptionsResolver $resolver): static
    {
        $resolver
            ->define('behaviors')
            ->default([])
            ->allowedValues(
                function(array $behaviors): bool {
                    $allowedBehaviors = BehaviorEnum::getNames();
                    foreach ($behaviors as $behavior) {
                        if (in_array($behavior, $allowedBehaviors->toArray(), true) === false) {
                            return false;
                        }
                    }

                    return true;
                }
            )
            ->allowedTypes(AllowedTypeEnum::ARRAY_OF_STRINGS->value);

        return $this;
    }
}
