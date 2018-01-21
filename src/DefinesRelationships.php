<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper;

use Stratadox\Hydration\ProducesProxyLoaders;

/**
 * Builds relationship instructions for in a property map.
 *
 * @author Stratadox
 * @package Stratadox\Hydrate
 */
interface DefinesRelationships extends InstructsHowToMap
{
    /**
     * Defines the class that contains the collection.
     *
     * @param string $class The container to use
     * @return DefinesRelationships
     */
    public function containedInA(
        string $class
    ) : DefinesRelationships;

    /**
     * Defines the object that produces proxy loaders.
     *
     * @param ProducesProxyLoaders $loader The class that produces the loaders
     * @return DefinesRelationships
     */
    public function loadedBy(
        ProducesProxyLoaders $loader
    ) : DefinesRelationships;

    /**
     * Defines the source data to be nested.
     *
     * @return DefinesRelationships
     */
    public function nested(
    ) : DefinesRelationships;

    /**
     * Add a property with optional mapping instructions.
     *
     * @param string                 $property    The property to define
     * @param InstructsHowToMap|null $instruction The instruction on how to map
     * @return DefinesRelationships
     */
    public function with(
        string $property,
        InstructsHowToMap $instruction = null
    ) : DefinesRelationships;
}
