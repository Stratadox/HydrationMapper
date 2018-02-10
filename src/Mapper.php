<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper;

use Stratadox\Hydration\Mapper\Instruction\Is;
use Stratadox\Hydration\Mapping\Properties;
use Stratadox\HydrationMapper\InstructsHowToMap;
use Stratadox\HydrationMapper\MakesMap;
use Stratadox\Hydrator\Hydrates;
use Stratadox\Hydrator\MappedHydrator;

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

    public function finish() : Hydrates
    {
        $class = $this->name;
        $properties = [];
        foreach ($this->properties as $name => $instruction) {
            $properties[] = $instruction->followFor($name);
        }
        return MappedHydrator::forThe($class, Properties::map(...$properties));
    }

    private function add(
        string $property,
        InstructsHowToMap $instruction = null
    ) : array
    {
        return $this->properties + [$property => $instruction ?: Is::string()];
    }
}
