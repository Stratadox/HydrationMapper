<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Instruction\Relation;

use Stratadox\Hydration\Mapper\DefinesRelationships;
use Stratadox\Hydration\Mapper\InstructsHowToMap;
use Stratadox\Hydration\ProducesProxyLoaders;

abstract class Relationship implements DefinesRelationships
{
    protected $class;
    protected $container;
    protected $loader;
    protected $shouldNest;
    protected $properties;

    final public function __construct(
        string $class,
        string $container = null,
        ProducesProxyLoaders $loader = null,
        bool $nested = false,
        array $properties = []
    ) {
        $this->class = $class;
        $this->container = $container;
        $this->loader = $loader;
        $this->shouldNest = $nested;
        $this->properties = $properties;
    }

    public static function ofThe(string $class) : DefinesRelationships
    {
        return new static($class);
    }

    public function containedInA(
        string $class
    ) : DefinesRelationships
    {
        return new static(
            $class,
            $this->container,
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
            $this->container,
            $this->loader,
            $this->shouldNest,
            $this->properties + [$property => $instruction]
        );
    }
}
