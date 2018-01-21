<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Instruction\Relation;

use Stratadox\Hydration\Hydrates;
use Stratadox\Hydration\Hydrator\MappedHydrator;
use Stratadox\Hydration\Mapper\DefinesRelationships;
use Stratadox\Hydration\Mapper\FindsKeys;
use Stratadox\Hydration\Mapper\InstructsHowToMap;
use Stratadox\Hydration\Mapper\Mapper;
use Stratadox\Hydration\ProducesProxyLoaders;

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

    final protected function __construct(
        string $class,
        FindsKeys $key = null,
        string $container = null,
        ProducesProxyLoaders $loader = null,
        bool $nested = false,
        array $properties = []
    ) {
        $this->class = $class;
        $this->key = $key;
        $this->container = $container;
        $this->loader = $loader;
        $this->shouldNest = $nested;
        $this->properties = $properties;
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
            $this->properties
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
            $this->properties
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
            $this->properties
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
            $this->properties + [$property => $instruction]
        );
    }

    protected function hydrator() : Hydrates
    {
        $mapped = Mapper::forThe($this->class);
        foreach ($this->properties as $property => $instruction) {
            $mapped = $mapped->property($property, $instruction);
        }
        return MappedHydrator::fromThis($mapped->map());
    }

    protected function keyOr(string $property) : string
    {
        return $this->key ? $this->key->find() : $property;
    }
}
