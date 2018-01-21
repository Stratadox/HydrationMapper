<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Unit\Instruction\Relation;

use ArrayObject;
use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Hydrator\ArrayHydrator;
use Stratadox\Hydration\Hydrator\MappedHydrator;
use Stratadox\Hydration\Hydrator\SimpleHydrator;
use Stratadox\Hydration\Hydrator\VariadicConstructor;
use Stratadox\Hydration\Mapper\Instruction\In;
use Stratadox\Hydration\Mapper\Instruction\Relation\HasMany;
use Stratadox\Hydration\Mapper\InvalidMapperConfiguration;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Chapter;
use Stratadox\Hydration\Mapper\Test\Stub\Book\ChapterLoaderFactory;
use Stratadox\Hydration\Mapper\Test\Stub\Book\ChapterProxy;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Chapters;
use Stratadox\Hydration\Mapper\Test\Stub\Book\ChaptersProxy;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Text;
use Stratadox\Hydration\Mapping\Mapping;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyNested;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyProxies;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneProxy;
use Stratadox\Hydration\Mapping\Property\Scalar\StringValue;
use Stratadox\Hydration\Proxying\AlterableCollectionEntryUpdaterFactory;
use Stratadox\Hydration\Proxying\ArrayEntryUpdaterFactory;
use Stratadox\Hydration\Proxying\PropertyUpdaterFactory;
use Stratadox\Hydration\Proxying\ProxyFactory;

/**
 * @covers \Stratadox\Hydration\Mapper\Instruction\Relation\HasMany
 * @covers \Stratadox\Hydration\Mapper\Instruction\Relation\Relationship
 * @covers \Stratadox\Hydration\Mapper\NoLoaderAvailable
 * @covers \Stratadox\Hydration\Mapper\NoContainerAvailable
 */
class HasMany_indicates_a_polygamic_relationship extends TestCase
{
    /** @scenario */
    function producing_a_nested_hasMany()
    {
        self::assertEquals(
            HasManyNested::inProperty('contents',
                VariadicConstructor::forThe(Chapters::class),
                MappedHydrator::fromThis(Mapping::ofThe(Chapter::class,
                    StringValue::inProperty('title')
                ))
            ),
            HasMany::ofThe(Chapter::class)
                ->nested()
                ->with('title')
                ->containedInA(Chapters::class)
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
                ->followFor('chapter')
        );
    }

    /** @scenario */
    function producing_a_lazy_hasMany()
    {
        self::assertEquals(
            HasOneProxy::inProperty('contents',
                ProxyFactory::fromThis(
                    SimpleHydrator::forThe(ChaptersProxy::class),
                    new ChapterLoaderFactory,
                    new PropertyUpdaterFactory
                )
            ),
            HasMany::ofThe(Chapter::class)
                ->containedInA(ChaptersProxy::class)
                ->loadedBy(new ChapterLoaderFactory())
                ->followFor('contents')
        );
    }

    /** @scenario */
    function producing_an_extra_lazy_hasMany()
    {
        self::assertEquals(
            HasManyProxies::inProperty('contents',
                VariadicConstructor::forThe(Chapters::class),
                ProxyFactory::fromThis(
                    SimpleHydrator::forThe(ChapterProxy::class),
                    new ChapterLoaderFactory,
                    new AlterableCollectionEntryUpdaterFactory
                )
            ),
            HasMany::ofThe(ChapterProxy::class)
                ->containedInA(Chapters::class)
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
                ->followFor('chapter')
        );
    }

    /** @scenario */
    function producing_an_array_hydrator_when_no_container_was_defined()
    {
        self::assertEquals(
            HasManyProxies::inProperty('chapter',
                ArrayHydrator::create(),
                ProxyFactory::fromThis(
                    SimpleHydrator::forThe(ChapterProxy::class),
                    new ChapterLoaderFactory,
                    new ArrayEntryUpdaterFactory
                )
            ),
            HasMany::ofThe(ChapterProxy::class)
                ->loadedBy(new ChapterLoaderFactory)
                ->followFor('chapter')
        );
    }

    /** @scenario */
    function lazy_collections_need_to_get_a_loader()
    {
        $this->expectException(InvalidMapperConfiguration::class);
        $this->expectExceptionMessage(sprintf(
            'Could not produce mapping due to a missing loader for class `%s`',
            Chapter::class
        ));
        HasMany::ofThe(Chapter::class)->followFor('chapter');
    }

    /** @scenario */
    function extra_lazy_collections_need_to_get_a_loader()
    {
        $this->expectException(InvalidMapperConfiguration::class);
        $this->expectExceptionMessage(sprintf(
            'Could not produce mapping due to a missing loader for class `%s`',
            ChapterProxy::class
        ));
        HasMany::ofThe(ChapterProxy::class)->followFor('chapter');
    }

    /** @scenario */
    function lazy_collections_need_to_get_a_container()
    {
        $this->expectException(InvalidMapperConfiguration::class);
        $this->expectExceptionMessage(sprintf(
            'Could not produce mapping due to a missing container for class `%s`',
            Chapter::class
        ));
        HasMany::ofThe(Chapter::class)
            ->loadedBy(new ChapterLoaderFactory)
            ->followFor('chapter');
    }
}
