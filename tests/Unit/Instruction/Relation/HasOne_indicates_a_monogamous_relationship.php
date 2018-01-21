<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Unit\Instruction;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Hydrator\MappedHydrator;
use Stratadox\Hydration\Mapper\Instruction\In;
use Stratadox\Hydration\Mapper\Instruction\Relation\HasOne;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Isbn;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Title;
use Stratadox\Hydration\Mapper\Test\Stub\Instruction\ItsANumber;
use Stratadox\Hydration\Mapping\Mapping;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneEmbedded;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneNested;
use Stratadox\Hydration\Mapping\Property\Scalar\IntegerValue;
use Stratadox\Hydration\Mapping\Property\Scalar\StringValue;

/**
 * @covers \Stratadox\Hydration\Mapper\Instruction\Relation\HasOne
 * @covers \Stratadox\Hydration\Mapper\Instruction\Relation\Relationship
 */
class HasOne_indicates_a_monogamous_relationship extends TestCase
{
    /** @scenario */
    function producing_an_embedded_hasOne()
    {
        self::assertEquals(
            HasOneEmbedded::inProperty('title',
                MappedHydrator::fromThis(Mapping::ofThe(Title::class,
                    StringValue::inProperty('title')
                ))
            ),
            HasOne::ofThe(Title::class)->with('title')->followFor('title')
        );
    }

    /** @scenario */
    function producing_a_nested_hasOne()
    {
        self::assertEquals(
            HasOneNested::inProperty('isbn',
                MappedHydrator::fromThis(Mapping::ofThe(Isbn::class,
                    StringValue::inProperty('code'),
                    IntegerValue::inProperty('version')
                ))
            ),
            HasOne::ofThe(Isbn::class)
                ->nested()
                ->with('code')
                ->with('version', ItsANumber::allRight())
                ->followFor('isbn')
        );
    }

    /** @scenario */
    function producing_a_nested_hasOne_with_different_key()
    {
        self::assertEquals(
            HasOneNested::inPropertyWithDifferentKey('isbn', 'id',
                MappedHydrator::fromThis(Mapping::ofThe(Isbn::class,
                    StringValue::inProperty('code'),
                    IntegerValue::inProperty('version')
                ))
            ),
            HasOne::ofThe(Isbn::class, In::key('id'))
                ->nested()
                ->with('code')
                ->with('version', ItsANumber::allRight())
                ->followFor('isbn')
        );
    }
}
