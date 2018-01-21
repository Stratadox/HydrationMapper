<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Unit;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapper\Mapper;
use Stratadox\Hydration\Mapper\Test\Stub\Author;
use Stratadox\Hydration\Mapping\Mapping;
use Stratadox\Hydration\Mapping\Property\Scalar\StringValue;

/**
 * @covers \Stratadox\Hydration\Mapper\Mapper
 */
class Mapper_collects_and_maps_properties extends TestCase
{
    /** @scenario */
    function mapping_an_author()
    {
        self::assertEquals(

            Mapping::ofThe(Author::class,
                StringValue::inProperty('firstName'),
                StringValue::inProperty('lastName')
            ),

            Mapper::forThe(Author::class)
                ->property('firstName')
                ->property('lastName')
                ->map()

        );
    }
}
