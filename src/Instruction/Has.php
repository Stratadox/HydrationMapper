<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Instruction;

use Stratadox\Hydration\Mapper\Instruction\Relation\HasMany;
use Stratadox\Hydration\Mapper\Instruction\Relation\HasOne;
use Stratadox\HydrationMapper\DefinesRelationships;
use Stratadox\HydrationMapper\FindsKeys;

/**
 * Accessor for HasOne and HasMany objects, essentially syntax sugar.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
final class Has
{
    private function __construct() {}

    /**
     * Define a has-one relationship with another class.
     *
     * @param string         $class The fully qualified class name.
     * @param FindsKeys|null $key   The input array offset (optional)
     * @return DefinesRelationships The relationship definition.
     */
    public static function one(
        string $class,
        FindsKeys $key = null
    ): DefinesRelationships {
        return HasOne::ofThe($class, $key);
    }

    /**
     * Define a has-many relationship with another class.
     *
     * @param string         $class The fully qualified class name.
     * @param FindsKeys|null $key   The input array offset (optional)
     * @return DefinesRelationships The relationship definition.
     */
    public static function many(
        string $class,
        FindsKeys $key = null
    ): DefinesRelationships {
        return HasMany::ofThe($class, $key);
    }
}
