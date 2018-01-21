<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper;

use BadMethodCallException;

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
