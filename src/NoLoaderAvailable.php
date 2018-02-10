<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper;

use BadMethodCallException as BadMethodCall;
use Stratadox\HydrationMapper\InvalidMapperConfiguration;

/**
 * Indicates that a loader was necessary but not available.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
final class NoLoaderAvailable extends BadMethodCall implements InvalidMapperConfiguration
{
    public static function for(string $class) : self
    {
        return new self(sprintf(
            'Could not produce mapping due to a missing loader for class `%s`',
            $class
        ));
    }
}
