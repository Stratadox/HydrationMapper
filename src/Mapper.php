<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper;

use Stratadox\Hydration\Hydrates;
use Stratadox\Hydration\Hydrator\MappedHydrator;
use Stratadox\Hydration\Mapper\Instruction\Is;
use Stratadox\Hydration\Mapping\Mapping;
use Stratadox\Hydration\MapsObject;

/**
 * Produces a mapping object.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
final class Mapper implements MakesMap
{
    private $name;
    private $properties;

    private function __construct(string $name, array $properties = [])
    {
        $this->name = $name;
        $this->properties = $properties;
    }

    public static function forThe(string $className) : self
    {
        return new self($className);
    }

    public function property(
        string $property,
        InstructsHowToMap $instruction = null
    ) : MakesMap
    {
        return new self($this->name, $this->add($property, $instruction));
    }

    public function hydrator() : Hydrates
    {
        return MappedHydrator::fromThis($this->map());
    }

    public function map() : MapsObject
    {
        $class = $this->name;
        $properties = [];
        foreach ($this->properties as $name => $instruction) {
            $properties[] = $instruction->followFor($name);
        }
        return Mapping::ofThe($class, ...$properties);
    }

    private function add(
        string $property,
        InstructsHowToMap $instruction = null
    ) : array
    {
        return $this->properties + [$property => $instruction ?: Is::string()];
    }
}
