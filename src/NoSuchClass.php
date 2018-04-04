<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapper;

use RuntimeException;
use function sprintf as withMessage;
use Stratadox\HydrationMapper\InvalidMapperConfiguration;

/**
 * Indicates that a class was not found.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
class NoSuchClass extends RuntimeException implements InvalidMapperConfiguration
{
    /**
     * Produces an exception for when a class could not be loaded.
     *
     * @param string $className The name of the class that could not be loaded.
     * @return NoSuchClass      The exception object.
     */
    public static function couldNotLoad(string $className): self
    {
        return new NoSuchClass(withMessage(
            'Could not produce mapping for non-existing class `%s`',
            $className
        ));
    }

    /**
     * Produces an exception for when a container class could not be loaded.
     *
     * @param string $className The name of the class that could not be loaded.
     * @return NoSuchClass      The exception object.
     */
    public static function couldNotLoadCollection(string $className): self
    {
        return new NoSuchClass(withMessage(
            'Could not produce mapping for non-existing container class `%s`',
            $className
        ));
    }
}
