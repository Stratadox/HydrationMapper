<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper;

use BadMethodCallException;

/**
 * Indicates that a loader was necessary but not available.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
final class NoLoaderAvailable
    extends BadMethodCallException
    implements InvalidMapperConfiguration
{
    public static function for(string $class) : InvalidMapperConfiguration
    {
        return new NoLoaderAvailable(sprintf(
            'Could not produce mapping due to a missing loader for class `%s`',
            $class
        ));
    }
}
