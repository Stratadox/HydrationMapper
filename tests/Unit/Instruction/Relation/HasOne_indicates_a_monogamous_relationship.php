<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Unit\Instruction;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapper\Instruction\In;
use Stratadox\Hydration\Mapper\Instruction\Relation\Choose;
use Stratadox\Hydration\Mapper\Instruction\Relation\HasOne;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Element;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Image;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Isbn;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Text;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Title;
use Stratadox\Hydration\Mapper\Test\Stub\Instruction\ItsANumber;
use Stratadox\Hydration\Mapping\Properties;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneEmbedded;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneNested;
use Stratadox\Hydration\Mapping\Property\Scalar\IntegerValue;
use Stratadox\Hydration\Mapping\Property\Scalar\StringValue;
use Stratadox\Hydrator\MappedHydrator;
use Stratadox\Hydrator\OneOfTheseHydrators;

/**
 * @covers \Stratadox\Hydration\Mapper\Instruction\Relation\HasOne
 * @covers \Stratadox\Hydration\Mapper\Instruction\Relation\Relationship
 */
class HasOne_indicates_a_monogamous_relationship extends TestCase
{
    /** @test */
    function producing_an_embedded_hasOne()
    {
        self::assertEquals(
            HasOneEmbedded::inProperty('title',
                MappedHydrator::forThe(Title::class, Properties::map(
                    StringValue::inProperty('title')
                ))
            ),
            HasOne::ofThe(Title::class)->with('title')->followFor('title')
        );
    }

    /** @test */
    function producing_a_nested_hasOne()
    {
        self::assertEquals(
            HasOneNested::inProperty('isbn',
                MappedHydrator::forThe(Isbn::class, Properties::map(
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

    /** @test */
    function producing_a_nested_hasOne_with_different_key()
    {
        self::assertEquals(
            HasOneNested::inPropertyWithDifferentKey('isbn', 'id',
                MappedHydrator::forThe(Isbn::class, Properties::map(
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

    /** @test */
    function producing_an_embedded_hasOne_with_polymorphism()
    {
        self::assertEquals(
            HasOneEmbedded::inProperty('element',
                OneOfTheseHydrators::decideBasedOnThe('type', [
                    'text' => MappedHydrator::forThe(Text::class, Properties::map(
                        StringValue::inProperty('text')
                    )),
                    'image' => MappedHydrator::forThe(Image::class, Properties::map(
                        StringValue::inProperty('src'),
                        StringValue::inProperty('alt')
                    )),
                ])
            ),
            HasOne::ofThe(Element::class)
                ->selectBy('type', [
                    'text' => Choose::the(Text::class)->with('text'),
                    'image' => Choose::the(Image::class)->with('src')->with('alt'),
                ])
                ->followFor('element')
        );
    }

    /** @test */
    function producing_a_nested_hasOne_with_polymorphism()
    {
        self::assertEquals(
            HasOneNested::inProperty('element',
                OneOfTheseHydrators::decideBasedOnThe('type', [
                    'text' => MappedHydrator::forThe(Text::class, Properties::map(
                        StringValue::inProperty('text')
                    )),
                    'image' => MappedHydrator::forThe(Image::class, Properties::map(
                        StringValue::inProperty('src'),
                        StringValue::inProperty('alt')
                    )),
                ])
            ),
            HasOne::ofThe(Element::class)
                ->selectBy('type', [
                    'text' => Choose::the(Text::class)->with('text'),
                    'image' => Choose::the(Image::class)->with('src')->with('alt'),
                ])
                ->nested()
                ->followFor('element')
        );
    }
}
