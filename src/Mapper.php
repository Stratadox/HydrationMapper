<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapper;

use Stratadox\Hydration\Mapper\Instruction\Is;
use Stratadox\Hydration\Mapping\Properties;
use Stratadox\HydrationMapper\InstructsHowToMap;
use Stratadox\HydrationMapper\MakesMap;
use Stratadox\Hydrator\Hydrates;
use Stratadox\Hydrator\MappedHydrator;
use Stratadox\Instantiator\CannotInstantiateThis;

/**
 * Builds a mapped hydrator, configured with mappings for the properties.
 *
 * @package Stratadox\Hydrate
 * @author  Stratadox
 */
final class Mapper implements MakesMap
{
    /** @var string */
    private $name;

    /** @var InstructsHowToMap[] */
    private $properties;

    private function __construct(string $name, array $properties = [])
    {
        $this->name = $name;
        $this->properties = $properties;
    }

    /**
     * Creates a builder that produces a mapped hydrator for a class.
     *
     * @see MappedHydrator
     * @param string $className The fully qualified name of the class to produce
     *                          a mapped hydrator for.
     * @return MakesMap         The builder for the mapped hydrator.
     */
    public static function forThe(string $className): MakesMap
    {
        return new self($className);
    }

    /** @inheritdoc */
    public function property(
        string $property,
        InstructsHowToMap $instruction = null
    ): MakesMap {
        return new self($this->name, $this->add($property, $instruction));
    }

    /** @inheritdoc */
    public function finish(): Hydrates
    {
        $class = $this->name;
        $properties = [];
        foreach ($this->properties as $name => $instruction) {
            $properties[] = $instruction->followFor($name);
        }
        try {
            return MappedHydrator::forThe($class, Properties::map(...$properties));
        } catch (CannotInstantiateThis $problem) {
            throw NoSuchClass::couldNotLoad($class);
        }
    }

    /**
     * Adds a property to the mapper.
     *
     * @param string                 $property    The name of the property.
     * @param InstructsHowToMap|null $instruction The instruction to follow.
     * @return InstructsHowToMap[]                The map of properties to
     *                                            mapping instructions.
     */
    private function add(
        string $property,
        ?InstructsHowToMap $instruction
    ): array {
        return $this->properties + [$property => $instruction ?: Is::string()];
    }
}
