<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper;

use Stratadox\Hydration\Hydrates;

/**
 * Represents a choice between concrete types.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
interface RepresentsChoice
{
    /**
     * Add a property with optional mapping instructions.
     *
     * @param string                 $property
     * @param InstructsHowToMap|null $howToMap
     * @return RepresentsChoice
     */
    public function with(
        string $property,
        InstructsHowToMap $howToMap = null
    ) : RepresentsChoice;

    /**
     * Finalise the process and produce a mapped hydrator.
     *
     * @return Hydrates
     */
    public function hydrator() : Hydrates;
}
