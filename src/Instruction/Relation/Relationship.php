<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Instruction\Relation;

use Stratadox\Hydration\Mapper\Mapper;
use Stratadox\Hydration\Mapping\Property\Check;
use Stratadox\HydrationMapper\DefinesRelationships;
use Stratadox\HydrationMapper\FindsKeys;
use Stratadox\HydrationMapper\InstructsHowToMap;
use Stratadox\HydrationMapper\InvalidMapperConfiguration;
use Stratadox\HydrationMapper\RepresentsChoice;
use Stratadox\HydrationMapping\MapsProperty;
use Stratadox\Hydrator\Hydrates;
use Stratadox\Hydrator\OneOfTheseHydrators;
use Stratadox\Proxy\ProducesProxyLoaders;
use function assert;
use Stratadox\Specification\Contract\Satisfiable;

/**
 * Defines a relationship with another class.
 *
 * @package Stratadox\Hydrate
 * @author  Stratadox
 */
abstract class Relationship implements DefinesRelationships
{
    /** @var string */
    protected $class;

    /** @var FindsKeys|null */
    protected $key;

    /** @var string|null */
    protected $container;

    /** @var ProducesProxyLoaders|null */
    protected $loader;

    /** @var bool */
    protected $shouldNest = false;

    /** @var (InstructsHowToMap|null)[] */
    protected $properties = [];

    /** @var string|null */
    protected $decisionKey;

    /** @var RepresentsChoice[] */
    protected $choices = [];

    /** @var Satisfiable|null */
    protected $constraint;

    private function __construct(string $class, FindsKeys $key = null)
    {
        $this->class = $class;
        $this->key = $key;
    }

    /**
     * Defines a new relationship with another class.
     *
     * @param string         $class The fully qualified class name.
     * @param FindsKeys|null $key   The input array offset (optional)
     * @return DefinesRelationships The relationship definition.
     */
    public static function ofThe(
        string $class,
        FindsKeys $key = null
    ): DefinesRelationships {
        return new static($class, $key);
    }

    /** @inheritdoc */
    public function containedInA(string $container): DefinesRelationships
    {
        $inst = clone $this;
        $inst->container = $container;
        return $inst;
    }

    /** @inheritdoc */
    public function loadedBy(ProducesProxyLoaders $loader): DefinesRelationships
    {
        $inst = clone $this;
        $inst->loader = $loader;
        return $inst;
    }

    /** @inheritdoc */
    public function nested(): DefinesRelationships
    {
        $inst = clone $this;
        $inst->shouldNest = true;
        return $inst;
    }

    /** @inheritdoc */
    public function with(
        string $property,
        InstructsHowToMap $instruction = null
    ): DefinesRelationships {
        $inst = clone $this;
        $inst->properties += [$property => $instruction];
        return $inst;
    }

    /** @inheritdoc */
    public function selectBy(
        string $decisionKey,
        array $choices
    ): DefinesRelationships {
        $inst = clone $this;
        $inst->decisionKey = $decisionKey;
        $inst->choices = $choices;
        return $inst;
    }

    public function that(Satisfiable $constraint): InstructsHowToMap
    {
        $inst = clone $this;
        $inst->constraint = $constraint;
        return $inst;
    }

    /**
     * Returns the key if one was provided, defaulting to the property name.
     *
     * @param string $property The property name to use as fallback.
     * @return string          The key to use as offset for the input data.
     */
    protected function keyOr(string $property): string
    {
        return $this->key ? $this->key->find() : $property;
    }

    /**
     * Produces a mapped hydrator according to the relationship configuration.
     *
     * @return Hydrates The hydrator for the relationship mapping.
     * @throws InvalidMapperConfiguration
     */
    protected function hydrator(): Hydrates
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

    protected function addConstraintTo(MapsProperty $mapping): MapsProperty
    {
        if (isset($this->constraint)) {
            $mapping = Check::that($this->constraint, $mapping);
        }
        return $mapping;
    }

    /**
     * Produces a multiple-choice hydrator.
     *
     * @return Hydrates The adapter that selects the hydrator.
     * @throws InvalidMapperConfiguration
     */
    private function choiceHydrator(): Hydrates
    {
        assert(isset($this->decisionKey));
        return OneOfTheseHydrators::decideBasedOnThe(
            $this->decisionKey,
            array_map(function (RepresentsChoice $choice): Hydrates {
                return $choice->finish();
            }, $this->choices)
        );
    }
}
