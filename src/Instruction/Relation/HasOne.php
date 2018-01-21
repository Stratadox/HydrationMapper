<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Instruction\Relation;

use Stratadox\Hydration\Hydrates;
use Stratadox\Hydration\Hydrator\MappedHydrator;
use Stratadox\Hydration\Mapper\Mapper;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneEmbedded;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneNested;
use Stratadox\Hydration\MapsProperty;

final class HasOne extends Relationship
{
    public function followFor(string $property) : MapsProperty
    {
        if ($this->shouldNest) {
            return HasOneNested::inProperty($property, $this->hydrator());
        }
        return HasOneEmbedded::inProperty($property, $this->hydrator());
    }

    private function hydrator() : Hydrates
    {
        $mapped = Mapper::forThe($this->class);
        foreach ($this->properties as $property => $instruction) {
            $mapped = $mapped->property($property, $instruction);
        }
        return MappedHydrator::fromThis($mapped->map());
    }
}
