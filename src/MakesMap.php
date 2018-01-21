<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper;

use Stratadox\Hydration\MapsObject;

interface MakesMap
{
    public function property(
        string $property,
        InstructsHowToMap $instruction = null
    ) : MakesMap;

    public function map() : MapsObject;
}
