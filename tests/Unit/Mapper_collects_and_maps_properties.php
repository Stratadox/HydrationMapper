<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Unit;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Hydrator\MappedHydrator;
use Stratadox\Hydration\Mapper\Mapper;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Author;
use Stratadox\Hydration\Mapper\Test\Stub\Foo\Foo;
use Stratadox\Hydration\Mapper\Test\Stub\Instruction\ItsANumber;
use Stratadox\Hydration\Mapping\Mapping;
use Stratadox\Hydration\Mapping\Property\Scalar\IntegerValue;
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

    /** @scenario */
    function mapping_with_instructions()
    {
        self::assertEquals(
            Mapping::ofThe(Foo::class,
                StringValue::inProperty('name'),
                IntegerValue::inProperty('number')
            ),
            Mapper::forThe(Foo::class)
                ->property('name')
                ->property('number', ItsANumber::allRight())
                ->map()
        );
    }

    /** @scenario */
    function producing_a_hydrator()
    {
        self::assertEquals(
            MappedHydrator::fromThis(Mapping::ofThe(Foo::class,
                StringValue::inProperty('name'),
                IntegerValue::inProperty('number')
            )),
            Mapper::forThe(Foo::class)
                ->property('name')
                ->property('number', ItsANumber::allRight())
                ->hydrator()
        );
    }
}
