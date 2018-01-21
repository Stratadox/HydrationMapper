<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Unit\Instruction;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Hydrator\MappedHydrator;
use Stratadox\Hydration\Hydrator\SimpleHydrator;
use Stratadox\Hydration\Hydrator\VariadicConstructor;
use Stratadox\Hydration\Mapper\Instruction\Has;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Chapter;
use Stratadox\Hydration\Mapper\Test\Stub\Book\ChapterLoaderFactory;
use Stratadox\Hydration\Mapper\Test\Stub\Book\ChapterProxy;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Contents;
use Stratadox\Hydration\Mapper\Test\Stub\Book\ContentsProxy;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Isbn;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Title;
use Stratadox\Hydration\Mapper\Test\Stub\Instruction\ItsANumber;
use Stratadox\Hydration\Mapping\Mapping;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyNested;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyProxies;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneEmbedded;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneNested;
use Stratadox\Hydration\Mapping\Property\Scalar\IntegerValue;
use Stratadox\Hydration\Mapping\Property\Scalar\StringValue;
use Stratadox\Hydration\Proxying\AlterableCollectionEntryUpdaterFactory;
use Stratadox\Hydration\Proxying\PropertyUpdaterFactory;
use Stratadox\Hydration\Proxying\ProxyFactory;

/**
 * @covers \Stratadox\Hydration\Mapper\Instruction\Has
 */
class Has_indicates_a_relationship extends TestCase
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
            Has::one(Title::class)->with('title')->followFor('title')
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
            Has::one(Isbn::class)
                ->nested()
                ->with('code')
                ->with('version', ItsANumber::allRight())
                ->followFor('isbn')
        );
    }

    /** @scenario */
    function producing_a_nested_hasMany()
    {
        self::assertEquals(
            HasManyNested::inProperty('contents',
                VariadicConstructor::forThe(Contents::class),
                MappedHydrator::fromThis(Mapping::ofThe(Chapter::class,
                    StringValue::inProperty('title')
                ))
            ),
            Has::many(Chapter::class)
                ->nested()
                ->with('title')
                ->containedInA(Contents::class)
                ->followFor('contents')
        );
    }

    /** @scenario */
    function producing_a_lazy_hasMany()
    {
        self::markTestSkipped('@todo: implement HasOneProxy mapping');
        self::assertEquals(
            HasOneProxy::inProperty('contents',
                ProxyFactory::fromThis(
                    SimpleHydrator::forThe(ContentsProxy::class),
                    new ChapterLoaderFactory,
                    new PropertyUpdaterFactory
                )
            ),
            Has::many(Chapter::class)
                ->containedInA(ContentsProxy::class)
                ->loadedBy(new ChapterLoaderFactory())
                ->followFor('contents')
        );
    }

    /** @scenario */
    function producing_an_extra_lazy_hasMany()
    {
        self::assertEquals(
            HasManyProxies::inPropertyWithDifferentKey('contents', 'chapters',
                VariadicConstructor::forThe(Contents::class),
                ProxyFactory::fromThis(
                    SimpleHydrator::forThe(ChapterProxy::class),
                    new ChapterLoaderFactory,
                    new AlterableCollectionEntryUpdaterFactory
                )
            ),
            Has::many(ChapterProxy::class)
                ->containedInA(Contents::class)
                ->loadedBy(new ChapterLoaderFactory())
                ->followFor('contents')
        );
    }

}
