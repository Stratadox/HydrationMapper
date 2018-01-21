<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Unit\Instruction;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapper\Instruction\In;
use Stratadox\Hydration\Mapping\Property\Scalar\StringValue;

/**
 * @covers \Stratadox\Hydration\Mapper\Instruction\In
 */
class In_changes_the_key extends TestCase
{
    /** @scenario */
    function using_the_value_from_a_different_key()
    {
        self::assertEquals(
            StringValue::inPropertyWithDifferentKey('foo', 'bar'),
            In::key('bar')->followFor('foo')
        );
        self::assertEquals(
            StringValue::inPropertyWithDifferentKey('bar', 'baz'),
            In::key('baz')->followFor('bar')
        );
    }

    /** @scenario */
    function finding_the_key()
    {
        self::assertEquals(
            'foo',
            In::key('foo')->find()
        );
    }
}
