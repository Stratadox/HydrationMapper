<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Instruction\Relation;

use Stratadox\Hydration\Mapping\Property\Relationship\HasOneEmbedded;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneNested;
use Stratadox\Hydration\MapsProperty;

final class HasOne extends Relationship
{
    public function followFor(string $property) : MapsProperty
    {
        if ($this->shouldNest) {
            return HasOneNested::inPropertyWithDifferentKey($property,
                $this->keyOr($property),
                $this->hydrator()
            );
        }
        return HasOneEmbedded::inProperty($property, $this->hydrator());
    }
}
