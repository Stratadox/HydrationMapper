<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper;

use Stratadox\Hydration\MapsObject;

/**
 * Builds the mapping for a class.
 *
 * @author Stratadox
 * @package Stratadox\Hydrate
 */
interface MakesMap
{
    /**
     * Add a property with optional mapping instructions.
     *
     * @param string                 $property
     * @param InstructsHowToMap|null $instruction
     * @return MakesMap
     */
    public function property(
        string $property,
        InstructsHowToMap $instruction = null
    ) : MakesMap;

    /**
     * Finalise the process and produce the object mapping.
     *
     * @return MapsObject
     */
    public function map() : MapsObject;
}
