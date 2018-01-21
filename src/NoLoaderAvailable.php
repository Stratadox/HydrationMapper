<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper;

use BadMethodCallException;

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
