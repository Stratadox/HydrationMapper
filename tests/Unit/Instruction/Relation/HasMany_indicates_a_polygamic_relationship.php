<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Unit\Instruction\Relation;

use ArrayObject;
use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Hydrator\MappedHydrator;
use Stratadox\Hydration\Hydrator\SimpleHydrator;
use Stratadox\Hydration\Hydrator\VariadicConstructor;
use Stratadox\Hydration\Mapper\Instruction\In;
use Stratadox\Hydration\Mapper\Instruction\Relation\HasMany;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Chapter;
use Stratadox\Hydration\Mapper\Test\Stub\Book\ChapterLoaderFactory;
use Stratadox\Hydration\Mapper\Test\Stub\Book\ChapterProxy;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Contents;
use Stratadox\Hydration\Mapper\Test\Stub\Book\ContentsProxy;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Text;
use Stratadox\Hydration\Mapping\Mapping;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyNested;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyProxies;
use Stratadox\Hydration\Mapping\Property\Scalar\StringValue;
use Stratadox\Hydration\Proxying\AlterableCollectionEntryUpdaterFactory;
use Stratadox\Hydration\Proxying\ArrayEntryUpdaterFactory;
use Stratadox\Hydration\Proxying\PropertyUpdaterFactory;
use Stratadox\Hydration\Proxying\ProxyFactory;

/**
 * @covers \Stratadox\Hydration\Mapper\Instruction\Relation\HasMany
 * @covers \Stratadox\Hydration\Mapper\Instruction\Relation\Relationship
 */
class HasMany_indicates_a_polygamic_relationship extends TestCase
{
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
            HasMany::ofThe(Chapter::class)
                ->nested()
                ->with('title')
                ->containedInA(Contents::class)
                ->followFor('contents')
        );
    }

    /** @scenario */
    function producing_a_nested_hasMany_with_different_key()
    {
        self::assertEquals(
            HasManyNested::inPropertyWithDifferentKey('chapter', 'data',
                VariadicConstructor::forThe(Chapter::class),
                MappedHydrator::fromThis(Mapping::ofThe(Text::class,
                    StringValue::inProperty('text')
                ))
            ),
            HasMany::ofThe(Text::class, In::key('data'))
                ->nested()
                ->with('text')
                ->containedInA(Chapter::class)
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
            HasMany::ofThe(Chapter::class)
                ->containedInA(ContentsProxy::class)
                ->loadedBy(new ChapterLoaderFactory())
                ->followFor('contents')
        );
    }

    /** @scenario */
    function producing_an_extra_lazy_hasMany()
    {
        self::assertEquals(
            HasManyProxies::inProperty('contents',
                VariadicConstructor::forThe(Contents::class),
                ProxyFactory::fromThis(
                    SimpleHydrator::forThe(ChapterProxy::class),
                    new ChapterLoaderFactory,
                    new AlterableCollectionEntryUpdaterFactory
                )
            ),
            HasMany::ofThe(ChapterProxy::class)
                ->containedInA(Contents::class)
                ->loadedBy(new ChapterLoaderFactory)
                ->followFor('contents')
        );
    }

    /** @scenario */
    function producing_an_extra_lazy_hasMany_with_different_key()
    {
        self::assertEquals(
            HasManyProxies::inPropertyWithDifferentKey('chapter', 'data',
                VariadicConstructor::forThe(ArrayObject::class),
                ProxyFactory::fromThis(
                    SimpleHydrator::forThe(ChapterProxy::class),
                    new ChapterLoaderFactory,
                    new ArrayEntryUpdaterFactory
                )
            ),
            HasMany::ofThe(ChapterProxy::class, In::key('data'))
                ->containedInA(ArrayObject::class)
                ->loadedBy(new ChapterLoaderFactory)
                ->followFor('contents')
        );
    }
}
