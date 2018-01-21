<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper;

use Stratadox\Hydration\MapsProperty;

interface InstructsHowToMap
{
    public function followFor(string $property) : MapsProperty;
}
