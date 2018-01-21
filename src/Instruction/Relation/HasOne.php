<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Instruction\Relation;

use Stratadox\Hydration\Mapper\DefinesRelationships;
use Stratadox\Hydration\MapsProperty;

final class HasOne implements DefinesRelationships
{
    public static function ofThe(string $class) : DefinesRelationships
    {
        return new self($class);
    }

    public function followFor(string $property) : MapsProperty
    {

    }
}
