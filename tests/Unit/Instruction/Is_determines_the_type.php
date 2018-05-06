<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Unit\Instruction;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapper\Instruction\Is;
use Stratadox\Hydration\Mapper\Test\Stub\Constraint\IsBetween;
use Stratadox\Hydration\Mapper\Test\Stub\Constraint\IsHigher;
use Stratadox\Hydration\Mapper\Test\Stub\Constraint\IsLower;
use Stratadox\Hydration\Mapping\Property\Check;
use Stratadox\Hydration\Mapping\Property\Scalar\BooleanValue;
use Stratadox\Hydration\Mapping\Property\Scalar\CanBeFloat;
use Stratadox\Hydration\Mapping\Property\Scalar\CanBeInteger;
use Stratadox\Hydration\Mapping\Property\Scalar\CanBeNull;
use Stratadox\Hydration\Mapping\Property\Scalar\FloatValue;
use Stratadox\Hydration\Mapping\Property\Scalar\IntegerValue;
use Stratadox\Hydration\Mapping\Property\Scalar\OriginalValue;
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

    /** @test */
    function producing_a_no_change_mapping()
    {
        self::assertEquals(
            OriginalValue::inProperty('foo'),
            Is::unchanged()->followFor('foo')
        );
    }

    /** @test */
    function producing_an_int_or_float_type_mapping()
    {
        self::assertEquals(
            CanBeInteger::or(FloatValue::inProperty('foo')),
            Is::number()->followFor('foo')
        );
    }

    /** @test */
    function producing_an_int_or_float_type_mapping_using_a_different_key()
    {
        self::assertEquals(
            CanBeInteger::or(FloatValue::inPropertyWithDifferentKey('foo', 'bar')),
            Is::numberInKey('bar')->followFor('foo')
        );
    }

    /** @test */
    function producing_a_mixed_mapping()
    {
        self::assertEquals(
            CanBeNull::or(
                CanBeInteger::or(
                    CanBeFloat::or(
                        StringValue::inProperty('foo')
                    )
                )
            ),
            Is::mixed()->followFor('foo')
        );
    }

    /** @test */
    function producing_a_mixed_mapping_using_a_different_key()
    {
        self::assertEquals(
            CanBeNull::or(
                CanBeInteger::or(
                    CanBeFloat::or(
                        StringValue::inPropertyWithDifferentKey('foo', 'bar')
                    )
                )
            ),
            Is::mixedInKey('bar')->followFor('foo')
        );
    }

    /** @test */
    function producing_a_nullable_string_mapping()
    {
        self::assertEquals(
            CanBeNull::or(StringValue::inProperty('foo')),
            Is::string()->nullable()->followFor('foo')
        );
    }

    /** @test */
    function producing_a_nullable_integer_mapping()
    {
        self::assertEquals(
            CanBeNull::or(IntegerValue::inProperty('foo')),
            Is::int()->nullable()->followFor('foo')
        );
    }

    /** @test */
    function producing_a_nullable_floating_point_mapping()
    {
        self::assertEquals(
            CanBeNull::or(FloatValue::inProperty('foo')),
            Is::float()->nullable()->followFor('foo')
        );
    }

    /** @test */
    function producing_a_nullable_boolean_mapping()
    {
        self::assertEquals(
            CanBeNull::or(BooleanValue::inProperty('foo')),
            Is::bool()->nullable()->followFor('foo')
        );
    }

    /** @test */
    function producing_a_nullable_number_mapping()
    {
        self::assertEquals(
            CanBeNull::or(CanBeInteger::or(FloatValue::inProperty('foo'))),
            Is::number()->nullable()->followFor('foo')
        );
    }

    /** @test */
    function producing_a_constrained_number_mapping()
    {
        self::assertEquals(
            Check::that(
                IsHigher::than(0)->and(IsLower::than(10)),
                CanBeInteger::or(FloatValue::inProperty('foo'))
            ),
            Is::number()->that(IsBetween::theNumbers(0, 10))->followFor('foo')
        );
    }
}
