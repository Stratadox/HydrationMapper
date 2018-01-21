<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Instruction\Relation;

use Stratadox\Hydration\LoadsProxiedObjects;
use Stratadox\Hydration\Mapper\DefinesRelationships;
use Stratadox\Hydration\Mapper\InstructsHowToMap;
use Stratadox\Hydration\MapsProperty;

final class HasMany implements DefinesRelationships
{
    public static function ofThe(string $class) : DefinesRelationships
    {
        return new self($class);
    }

    public function containedInA(
        string $class
    ) : DefinesRelationships
    {

    }

    public function loadedBy(
        LoadsProxiedObjects $loader
    ) : DefinesRelationships
    {

    }

    public function nested() : DefinesRelationships
    {

    }

    public function with(
        string $property,
        InstructsHowToMap $instruction = null
    ) : DefinesRelationships
    {

    }

    public function followFor(string $property) : MapsProperty
    {

    }
}
