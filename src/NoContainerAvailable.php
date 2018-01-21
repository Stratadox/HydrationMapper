<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper;

use BadMethodCallException;

/**
 * Indicates that a container was necessary but not available.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
final class NoContainerAvailable
    extends BadMethodCallException
    implements InvalidMapperConfiguration
{
    public static function for(string $class) : InvalidMapperConfiguration
    {
        return new NoContainerAvailable(sprintf(
            'Could not produce mapping due to a missing container for class `%s`',
            $class
        ));
    }
}
