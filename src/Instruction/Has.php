<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Instruction;

use Stratadox\Hydration\Mapper\DefinesRelationships;
use Stratadox\Hydration\Mapper\FindsKeys;
use Stratadox\Hydration\Mapper\Instruction\Relation\HasMany;
use Stratadox\Hydration\Mapper\Instruction\Relation\HasOne;

final class Has
{
    private function __construct() {}

    public static function one(
        string $class,
        FindsKeys $key = null
    ) : DefinesRelationships
    {
        return HasOne::ofThe($class, $key);
    }

    public static function many(
        string $class,
        FindsKeys $key = null
    ) : DefinesRelationships
    {
        return HasMany::ofThe($class, $key);
    }
}
