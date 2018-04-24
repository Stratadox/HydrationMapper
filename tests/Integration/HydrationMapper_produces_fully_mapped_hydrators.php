<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Integration;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapper\Instruction\Call;
use Stratadox\Hydration\Mapper\Instruction\Has;
use Stratadox\Hydration\Mapper\Instruction\In;
use Stratadox\Hydration\Mapper\Mapper;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Author;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Book;
use Stratadox\Hydration\Mapper\Test\Stub\Book\ChapterLoaderFactory;
use Stratadox\Hydration\Mapper\Test\Stub\Book\ChapterProxy;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Chapters;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Isbn;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Title;
use Stratadox\Hydration\Mapping\Properties;
use Stratadox\Hydration\Mapping\Property\Dynamic\ClosureResult;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyProxies;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneEmbedded;
use Stratadox\Hydration\Mapping\Property\Scalar\StringValue;
use Stratadox\Hydrator\MappedHydrator;
use Stratadox\Hydrator\SimpleHydrator;
use Stratadox\Hydrator\VariadicConstructor;
use Stratadox\Proxy\AlterableCollectionEntryUpdaterFactory;
use Stratadox\Proxy\ProxyFactory;
use function strlen;

/**
 * @coversNothing
 */
class HydrationMapper_produces_fully_mapped_hydrators extends TestCase
{
    /** @test */
    function building_a_mapped_hydrator_for_Books()
    {
        $expected = MappedHydrator::forThe(Book::class, Properties::map(
            HasOneEmbedded::inProperty('title',
                MappedHydrator::forThe(Title::class, Properties::map(
                    StringValue::inProperty('title')
                ))
            ),
            HasOneEmbedded::inProperty('isbn',
                MappedHydrator::forThe(Isbn::class, Properties::map(
                    StringValue::inPropertyWithDifferentKey('code', 'id'),
                    ClosureResult::inProperty('version', function ($data) {
                        return strlen($data['id']);
                    })
                ))
            ),
            HasOneEmbedded::inProperty('author',
                MappedHydrator::forThe(Author::class, Properties::map(
                    StringValue::inPropertyWithDifferentKey('firstName', 'author_first_name'),
                    StringValue::inPropertyWithDifferentKey('lastName', 'author_last_name')
                ))
            ),
            HasManyProxies::inPropertyWithDifferentKey('contents', 'chapters',
                VariadicConstructor::forThe(Chapters::class),
                ProxyFactory::fromThis(
                    SimpleHydrator::forThe(ChapterProxy::class),
                    new ChapterLoaderFactory,
                    new AlterableCollectionEntryUpdaterFactory
                )
            ),
            StringValue::inProperty('format')
        ));

        $actual = Mapper::forThe(Book::class)
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
                ->containedInA(Chapters::class)
                ->loadedBy(new ChapterLoaderFactory)
            )
            ->property('format')
            ->finish();

        self::assertEquals($expected, $actual);
    }
}
