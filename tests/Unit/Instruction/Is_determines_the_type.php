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
    /** @scenario */
    function producing_a_string_mapping()
    {
        self::assertEquals(
            StringValue::inProperty('foo'),
            Is::string()->followFor('foo')
        );
    }

    /** @scenario */
    function producing_an_integer_mapping()
    {
        self::assertEquals(
            IntegerValue::inProperty('foo'),
            Is::int()->followFor('foo')
        );
    }

    /** @scenario */
    function producing_a_floating_point_mapping()
    {
        self::assertEquals(
            FloatValue::inProperty('foo'),
            Is::float()->followFor('foo')
        );
    }

    /** @scenario */
    function producing_a_boolean_mapping()
    {
        self::assertEquals(
            BooleanValue::inProperty('foo'),
            Is::bool()->followFor('foo')
        );
    }
}
