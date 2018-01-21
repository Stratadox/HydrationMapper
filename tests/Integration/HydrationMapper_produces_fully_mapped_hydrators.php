<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Integration;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Hydrator\MappedHydrator;
use Stratadox\Hydration\Hydrator\SimpleHydrator;
use Stratadox\Hydration\Hydrator\VariadicConstructor;
use Stratadox\Hydration\Mapper\Mapper;
use Stratadox\Hydration\Mapper\Instruction\Call;
use Stratadox\Hydration\Mapper\Instruction\Has;
use Stratadox\Hydration\Mapper\Instruction\In;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Author;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Book;
use Stratadox\Hydration\Mapper\Test\Stub\Book\ChapterLoaderFactory;
use Stratadox\Hydration\Mapper\Test\Stub\Book\ChapterProxy;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Contents;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Isbn;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Title;
use Stratadox\Hydration\Mapping\Mapping;
use Stratadox\Hydration\Mapping\Property\Dynamic\ClosureResult;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyProxies;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneEmbedded;
use Stratadox\Hydration\Mapping\Property\Scalar\StringValue;
use Stratadox\Hydration\Proxying\AlterableCollectionEntryUpdaterFactory;
use Stratadox\Hydration\Proxying\ProxyFactory;
use function strlen;

/**
 * @coversNothing
 */
class HydrationMapper_produces_fully_mapped_hydrators extends TestCase
{
    /** @scenario */
    function building_a_mapped_hydrator_for_Books()
    {
        $expectedMapping = Mapping::ofThe(Book::class,
            HasOneEmbedded::inProperty('title',
                MappedHydrator::fromThis(Mapping::ofThe(Title::class,
                    StringValue::inProperty('title')
                ))
            ),
            HasOneEmbedded::inProperty('isbn',
                MappedHydrator::fromThis(Mapping::ofThe(Isbn::class,
                    StringValue::inPropertyWithDifferentKey('code', 'id'),
                    ClosureResult::inProperty('version', function ($data) {
                        return strlen($data['id']);
                    })
                ))
            ),
            HasOneEmbedded::inProperty('author',
                MappedHydrator::fromThis(Mapping::ofThe(Author::class,
                    StringValue::inPropertyWithDifferentKey('firstName', 'author_first_name'),
                    StringValue::inPropertyWithDifferentKey('lastName', 'author_last_name')
                ))
            ),
            HasManyProxies::inPropertyWithDifferentKey('contents', 'chapters',
                VariadicConstructor::forThe(Contents::class),
                ProxyFactory::fromThis(
                    SimpleHydrator::forThe(ChapterProxy::class),
                    new ChapterLoaderFactory,
                    new AlterableCollectionEntryUpdaterFactory
                )
            ),
            StringValue::inProperty('format')
        );

        $actualMapping = Mapper::forThe(Book::class)
            ->property('title', Has::one(Title::class)->with('title'))
            ->property('isbn', Has::one(Isbn::class)
                ->with('code', In::key('id'))
                ->with('version', Call::the(function ($data) {
                    return strlen($data['id']);
                }))
            )
            ->property('author', Has::one(Author::class)
                ->with('firstName', In::key('author_first_name'))
                ->with('lastName', In::key('author_last_name'))
            )
            ->property('contents', Has::many(ChapterProxy::class, In::key('chapters'))
                ->containedInA(Contents::class)
                ->loadedBy(new ChapterLoaderFactory)
            )
            ->property('format')
            ->map();

        self::assertEquals($expectedMapping, $actualMapping);
    }
}
