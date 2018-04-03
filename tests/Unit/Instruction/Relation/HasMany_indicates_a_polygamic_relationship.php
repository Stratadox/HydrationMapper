<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Unit\Instruction\Relation;

use ArrayObject;
use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapper\Instruction\In;
use Stratadox\Hydration\Mapper\Instruction\Relation\Choose;
use Stratadox\Hydration\Mapper\Instruction\Relation\HasMany;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Chapter;
use Stratadox\Hydration\Mapper\Test\Stub\Book\ChapterLoaderFactory;
use Stratadox\Hydration\Mapper\Test\Stub\Book\ChapterProxy;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Chapters;
use Stratadox\Hydration\Mapper\Test\Stub\Book\ChaptersProxy;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Element;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Elements;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Image;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Text;
use Stratadox\Hydration\Mapping\Properties;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyNested;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyProxies;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneProxy;
use Stratadox\Hydration\Mapping\Property\Scalar\StringValue;
use Stratadox\HydrationMapper\InvalidMapperConfiguration;
use Stratadox\Hydrator\ArrayHydrator;
use Stratadox\Hydrator\MappedHydrator;
use Stratadox\Hydrator\OneOfTheseHydrators;
use Stratadox\Hydrator\SimpleHydrator;
use Stratadox\Hydrator\VariadicConstructor;
use Stratadox\Proxy\AlterableCollectionEntryUpdaterFactory;
use Stratadox\Proxy\ArrayEntryUpdaterFactory;
use Stratadox\Proxy\PropertyUpdaterFactory;
use Stratadox\Proxy\ProxyFactory;

/**
 * @covers \Stratadox\Hydration\Mapper\Instruction\Relation\HasMany
 * @covers \Stratadox\Hydration\Mapper\Instruction\Relation\Relationship
 *
 * @covers \Stratadox\Hydration\Mapper\NoLoaderAvailable
 * @covers \Stratadox\Hydration\Mapper\NoContainerAvailable
 * @covers \Stratadox\Hydration\Mapper\NoSuchClass
 */
class HasMany_indicates_a_polygamic_relationship extends TestCase
{
    /** @test */
    function producing_a_nested_hasMany()
    {
        self::assertEquals(
            HasManyNested::inProperty('contents',
                VariadicConstructor::forThe(Chapters::class),
                MappedHydrator::forThe(Chapter::class, Properties::map(
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

    /** @test */
    function producing_a_nested_hasMany_with_different_key()
    {
        self::assertEquals(
            HasManyNested::inPropertyWithDifferentKey('chapter', 'data',
                VariadicConstructor::forThe(Chapter::class),
                MappedHydrator::forThe(Text::class, Properties::map(
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

    /** @test */
    function producing_a_nested_hasMany_with_polymorphism()
    {
        self::assertEquals(
            HasManyNested::inProperty('elements',
                VariadicConstructor::forThe(Elements::class),
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
            HasMany::ofThe(Element::class)
                ->selectBy('type', [
                    'text' => Choose::the(Text::class)->with('text'),
                    'image' => Choose::the(Image::class)->with('src')->with('alt'),
                ])
                ->nested()
                ->with('title')
                ->containedInA(Elements::class)
                ->followFor('elements')
        );
    }

    /** @test */
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

    /** @test */
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

    /** @test */
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

    /** @test */
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

    /** @test */
    function lazy_collections_need_to_get_a_loader()
    {
        $this->expectException(InvalidMapperConfiguration::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(sprintf(
            'Could not produce mapping due to a missing loader for class `%s`',
            Chapter::class
        ));
        HasMany::ofThe(Chapter::class)->followFor('chapter');
    }

    /** @test */
    function extra_lazy_collections_need_to_get_a_loader()
    {
        $this->expectException(InvalidMapperConfiguration::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(sprintf(
            'Could not produce mapping due to a missing loader for class `%s`',
            ChapterProxy::class
        ));
        HasMany::ofThe(ChapterProxy::class)->followFor('chapter');
    }

    /** @test */
    function lazy_collections_need_to_get_a_container()
    {
        $this->expectException(InvalidMapperConfiguration::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(sprintf(
            'Could not produce mapping due to a missing container for class `%s`',
            Chapter::class
        ));
        HasMany::ofThe(Chapter::class)
            ->loadedBy(new ChapterLoaderFactory)
            ->followFor('chapter');
    }

    /** @test */
    function cannot_map_nested_collections_if_the_item_class_does_not_exist()
    {
        $this->expectException(InvalidMapperConfiguration::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Could not produce mapping for non-existing class ' .
            '`Stratadox\Not\An\Actual\Class`'
        );
        HasMany::ofThe('Stratadox\Not\An\Actual\Class')
            ->nested()
            ->followFor('foo');
    }

    /** @test */
    function cannot_map_nested_collections_if_the_collection_class_does_not_exist()
    {
        $this->expectException(InvalidMapperConfiguration::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Could not produce mapping for non-existing container class ' .
            '`Stratadox\Not\An\Actual\Class`'
        );
        HasMany::ofThe(Chapter::class)
            ->nested()
            ->containedInA('Stratadox\Not\An\Actual\Class')
            ->followFor('foo');
    }

    /** @test */
    function cannot_map_lazy_collections_if_the_container_does_not_exist()
    {
        $this->expectException(InvalidMapperConfiguration::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Could not produce mapping for non-existing container class ' .
            '`Stratadox\Not\An\Actual\Class`'
        );
        HasMany::ofThe(Chapter::class)
            ->loadedBy(new ChapterLoaderFactory)
            ->containedInA('Stratadox\Not\An\Actual\Class')
            ->followFor('foo');
    }
}
