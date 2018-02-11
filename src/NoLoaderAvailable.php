<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper;

use BadMethodCallException as BadMethodCall;
use function sprintf as withMessage;
use Stratadox\HydrationMapper\InvalidMapperConfiguration as InvalidMapper;

/**
 * Indicates that a loader was necessary but not available.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
final class NoLoaderAvailable extends BadMethodCall implements InvalidMapper
{
    /**
     * Produce an exception for when there is no loader defined for a class.
     *.
     * @param string $class The class that is missing a loader.
     * @return self         The exception object.
     */
    public static function whilstRequiredFor(string $class) : NoLoaderAvailable
    {
        return new NoLoaderAvailable(withMessage(
            'Could not produce mapping due to a missing loader for class `%s`',
            $class
        ));
    }
}
