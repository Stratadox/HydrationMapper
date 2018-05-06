<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Unit\Instruction;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapper\Instruction\In;
use Stratadox\Hydration\Mapper\Test\Stub\Constraint\IsNotEmpty;
use Stratadox\Hydration\Mapping\Property\Check;
use Stratadox\Hydration\Mapping\Property\Scalar\OriginalValue;

/**
 * @covers \Stratadox\Hydration\Mapper\Instruction\In
 */
class In_changes_the_key extends TestCase
{
    /**
     * @test
     * @dataProvider keysAndProperties
     */
    function using_the_value_from_a_different_key_with($property, $key)
    {
        self::assertEquals(
            OriginalValue::inPropertyWithDifferentKey($property, $key),
            In::key($key)->followFor($property)
        );
    }

    /** @test */
    function using_the_value_from_a_different_key_with_a_constraint()
    {
        self::assertEquals(
            Check::that(
                IsNotEmpty::text(),
                OriginalValue::inPropertyWithDifferentKey('foo', 'bar')
            ),
            In::key('bar')->that(IsNotEmpty::text())->followFor('foo')
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

    public function keysAndProperties(): array
    {
        return [
            'Property foo in key bar' => ['foo', 'bar'],
            'Property bar in key baz' => ['bar', 'baz'],
            'Property ownName in key own_name' => ['ownName', 'own_name'],
        ];
    }
}
