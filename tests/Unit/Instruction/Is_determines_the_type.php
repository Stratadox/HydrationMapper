<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Unit\Instruction;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapper\Instruction\Is;
use Stratadox\Hydration\Mapping\Property\Scalar\BooleanValue;
use Stratadox\Hydration\Mapping\Property\Scalar\FloatValue;
use Stratadox\Hydration\Mapping\Property\Scalar\IntegerValue;
use Stratadox\Hydration\Mapping\Property\Scalar\StringValue;

/**
 * @covers \Stratadox\Hydration\Mapper\Instruction\Is
 */
class Is_determines_the_type extends TestCase
{
    /** @test */
    function producing_a_string_mapping()
    {
        self::assertEquals(
            StringValue::inProperty('foo'),
            Is::string()->followFor('foo')
        );
    }

    /** @test */
    function producing_an_integer_mapping()
    {
        self::assertEquals(
            IntegerValue::inProperty('foo'),
            Is::int()->followFor('foo')
        );
    }

    /** @test */
    function producing_a_floating_point_mapping()
    {
        self::assertEquals(
            FloatValue::inProperty('foo'),
            Is::float()->followFor('foo')
        );
    }

    /** @test */
    function producing_a_boolean_mapping()
    {
        self::assertEquals(
            BooleanValue::inProperty('foo'),
            Is::bool()->followFor('foo')
        );
    }

    /** @test */
    function producing_a_string_mapping_using_a_different_key()
    {
        self::assertEquals(
            StringValue::inPropertyWithDifferentKey('foo', 'bar'),
            Is::stringInKey('bar')->followFor('foo')
        );
    }

    /** @test */
    function producing_an_integer_mapping_using_a_different_key()
    {
        self::assertEquals(
            IntegerValue::inPropertyWithDifferentKey('foo', 'bar'),
            Is::intInKey('bar')->followFor('foo')
        );
    }

    /** @test */
    function producing_a_floating_point_mapping_using_a_different_key()
    {
        self::assertEquals(
            FloatValue::inPropertyWithDifferentKey('foo', 'bar'),
            Is::floatInKey('bar')->followFor('foo')
        );
    }

    /** @test */
    function producing_a_boolean_mapping_using_a_different_key()
    {
        self::assertEquals(
            BooleanValue::inPropertyWithDifferentKey('foo', 'bar'),
            Is::boolInKey('bar')->followFor('foo')
        );
    }
}
