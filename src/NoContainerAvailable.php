<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper;

use BadMethodCallException as BadMethodCall;
use function sprintf as withMessage;
use Stratadox\HydrationMapper\InvalidMapperConfiguration as InvalidMapper;

/**
 * Indicates that a container was necessary but not available.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
final class NoContainerAvailable extends BadMethodCall implements InvalidMapper
{
    /**
     * Produce an exception for when there is no container defined for a class.
     *.
     * @param string $class The class that is missing a container.
     * @return self         The exception object.
     */
    public static function whilstRequiredFor(string $class): NoContainerAvailable
    {
        return new NoContainerAvailable(withMessage(
            'Could not produce mapping due to a missing container for class `%s`',
            $class
        ));
    }
}
