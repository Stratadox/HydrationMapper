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
    /** @var string */
    protected $class;
    /** @var FindsKeys */
    protected $key;
    /** @var ?string */
    protected $container;
    /** @var ?ProducesProxyLoaders */
    protected $loader;
    /** @var bool */
    protected $shouldNest = false;
    /** @var InstructsHowToMap[] */
    protected $properties = [];
    /** @var ?string */
    protected $decisionKey;
    /** @var RepresentsChoice[] */
    protected $choices = [];

    final protected function __construct(string $class, FindsKeys $key = null)
    {
        $this->class = $class;
        $this->key = $key;
    }

    public static function ofThe(
        string $class,
        FindsKeys $key = null
    ) : DefinesRelationships
    {
        return new static($class, $key);
    }

    public function containedInA(string $container) : DefinesRelationships
    {
        $inst = clone $this;
        $inst->container = $container;
        return $inst;
    }

    public function loadedBy(ProducesProxyLoaders $loader) : DefinesRelationships
    {
        $inst = clone $this;
        $inst->loader = $loader;
        return $inst;
    }

    public function nested() : DefinesRelationships
    {
        $inst = clone $this;
        $inst->shouldNest = true;
        return $inst;
    }

    public function with(
        string $property,
        InstructsHowToMap $instruction = null
    ) : DefinesRelationships
    {
        $inst = clone $this;
        $inst->properties += [$property => $instruction];
        return $inst;
    }

    public function selectBy(
        string $decisionKey,
        array $choices
    ) : DefinesRelationships
    {
        $inst = clone $this;
        $inst->decisionKey = $decisionKey;
        $inst->choices = $choices;
        return $inst;
    }

    protected function keyOr(string $property) : string
    {
        return $this->key ? $this->key->find() : $property;
    }

    protected function hydrator() : Hydrates
    {
        if (isset($this->decisionKey)) {
            return $this->choiceHydrator();
        } else {
            $mapped = Mapper::forThe($this->class);
            foreach ($this->properties as $property => $instruction) {
                $mapped = $mapped->property($property, $instruction);
            }
            return $mapped->finish();
        }
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
