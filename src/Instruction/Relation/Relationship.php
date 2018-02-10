<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Instruction\Relation;

use Stratadox\Hydration\Mapper\Mapper;
use Stratadox\HydrationMapper\DefinesRelationships;
use Stratadox\HydrationMapper\FindsKeys;
use Stratadox\HydrationMapper\InstructsHowToMap;
use Stratadox\HydrationMapper\RepresentsChoice;
use Stratadox\Hydrator\Hydrates;
use Stratadox\Hydrator\OneOfTheseHydrators;
use Stratadox\Proxy\ProducesProxyLoaders;

/**
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
abstract class Relationship implements DefinesRelationships
{
    protected $class;
    protected $key;
    protected $container;
    protected $loader;
    protected $shouldNest;
    protected $properties;
    protected $decisionKey;
    protected $choices;

    final protected function __construct(
        string $class,
        FindsKeys $key = null,
        string $container = null,
        ProducesProxyLoaders $loader = null,
        bool $nested = false,
        array $properties = [],
        string $decisionKey = null,
        array $choices = []
    ) {
        $this->class = $class;
        $this->key = $key;
        $this->container = $container;
        $this->loader = $loader;
        $this->shouldNest = $nested;
        $this->properties = $properties;
        $this->decisionKey = $decisionKey;
        $this->choices = $choices;
    }

    public static function ofThe(
        string $class,
        FindsKeys $key = null
    ) : DefinesRelationships
    {
        return new static($class, $key);
    }

    public function containedInA(
        string $container
    ) : DefinesRelationships
    {
        return new static(
            $this->class,
            $this->key,
            $container,
            $this->loader,
            $this->shouldNest,
            $this->properties,
            $this->decisionKey,
            $this->choices
        );
    }

    public function loadedBy(
        ProducesProxyLoaders $loader
    ) : DefinesRelationships
    {
        return new static(
            $this->class,
            $this->key,
            $this->container,
            $loader,
            $this->shouldNest,
            $this->properties,
            $this->decisionKey,
            $this->choices
        );
    }

    public function nested() : DefinesRelationships
    {
        return new static(
            $this->class,
            $this->key,
            $this->container,
            $this->loader,
            true,
            $this->properties,
            $this->decisionKey,
            $this->choices
        );
    }

    public function with(
        string $property,
        InstructsHowToMap $instruction = null
    ) : DefinesRelationships
    {
        return new static(
            $this->class,
            $this->key,
            $this->container,
            $this->loader,
            $this->shouldNest,
            $this->properties + [$property => $instruction],
            $this->decisionKey,
            $this->choices
        );
    }

    public function selectBy(
        string $decisionKey,
        array $choices
    ) : DefinesRelationships
    {
        return new static(
            $this->class,
            $this->key,
            $this->container,
            $this->loader,
            $this->shouldNest,
            $this->properties,
            $decisionKey,
            $choices
        );
    }

    protected function keyOr(string $property) : string
    {
        return $this->key ? $this->key->find() : $property;
    }

    protected function hydrator() : Hydrates
    {
        if (isset($this->decisionKey)) {
            return $this->choiceHydrator();
        }
        $mapped = Mapper::forThe($this->class);
        foreach ($this->properties as $property => $instruction) {
            $mapped = $mapped->property($property, $instruction);
        }
        return $mapped->finish();
    }

    private function choiceHydrator() : Hydrates
    {
        return OneOfTheseHydrators::decideBasedOnThe(
            $this->decisionKey,
            array_map(function (RepresentsChoice $choice) {
                return $choice->finish();
            }, $this->choices)
        );
    }
}
