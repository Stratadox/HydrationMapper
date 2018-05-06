<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Unit\Instruction;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapper\Instruction\In;
use Stratadox\Hydration\Mapping\Property\Scalar\OriginalValue;

/**
 * @covers \Stratadox\Hydration\Mapper\Instruction\In
 */
class In_changes_the_key extends TestCase
{
    /** @test */
    function using_the_value_from_a_different_key()
    {
        self::assertEquals(
            OriginalValue::inPropertyWithDifferentKey('foo', 'bar'),
            In::key('bar')->followFor('foo')
        );
        self::assertEquals(
            OriginalValue::inPropertyWithDifferentKey('bar', 'baz'),
            In::key('baz')->followFor('bar')
        );
    }

    /** @test */
    function finding_the_key()
    {
        self::assertEquals(
            'foo',
            In::key('foo')->find()
        );
    }
}
