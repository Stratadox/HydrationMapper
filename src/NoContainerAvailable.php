<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper;

use BadMethodCallException as BadMethodCall;
use Stratadox\HydrationMapper\InvalidMapperConfiguration;

/**
 * Indicates that a container was necessary but not available.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
final class NoContainerAvailable extends BadMethodCall implements InvalidMapperConfiguration
{
    public static function for(string $class) : self
    {
        return new self(sprintf(
            'Could not produce mapping due to a missing container for class `%s`',
            $class
        ));
    }
}
