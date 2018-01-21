<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper;

use Stratadox\Hydration\Hydrates;

interface RepresentsChoice
{
    public function with(
        string $property,
        InstructsHowToMap $howToMap = null
    ) : RepresentsChoice;

    public function hydrator() : Hydrates;
}
