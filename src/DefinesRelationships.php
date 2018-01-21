<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper;

use Stratadox\Hydration\ProducesProxyLoaders;

interface DefinesRelationships extends InstructsHowToMap
{
    public function containedInA(
        string $class
    ) : DefinesRelationships;

    public function loadedBy(
        ProducesProxyLoaders $loader
    ) : DefinesRelationships;

    public function nested(
    ) : DefinesRelationships;

    public function with(
        string $property,
        InstructsHowToMap $instruction = null
    ) : DefinesRelationships;
}
