<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Instruction;

use Stratadox\Hydration\Mapper\DefinesRelationships;
use Stratadox\Hydration\Mapper\Instruction\Relation\HasMany;
use Stratadox\Hydration\Mapper\Instruction\Relation\HasOne;

final class Has
{
    private function __construct() {}

    public static function one(string $class) : DefinesRelationships
    {
        return HasOne::ofThe($class);
    }

    public static function many(string $class) : DefinesRelationships
    {
        return HasMany::ofThe($class);
    }
}
