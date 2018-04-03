<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapper;

use ReflectionException;
use RuntimeException;
use function sprintf as withMessage;
use Stratadox\HydrationMapper\InvalidMapperConfiguration;

class NoSuchClass extends RuntimeException implements InvalidMapperConfiguration
{
    public static function couldNotLoad(string $property): self
    {
        return new NoSuchClass(withMessage(
            'Could not produce mapping for non-existing class `%s`',
            $property
        ));
    }
}
