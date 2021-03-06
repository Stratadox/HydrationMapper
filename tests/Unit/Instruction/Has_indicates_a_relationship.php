<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Unit\Instruction;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapper\Instruction\Has;
use Stratadox\Hydration\Mapper\Instruction\In;
use Stratadox\Hydration\Mapper\Instruction\Relation\HasMany;
use Stratadox\Hydration\Mapper\Instruction\Relation\HasOne;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Title;

/**
 * @covers \Stratadox\Hydration\Mapper\Instruction\Has
 */
class Has_indicates_a_relationship extends TestCase
{
    /** @test */
    function producing_a_hasOne()
    {
        self::assertEquals(
            HasOne::ofThe(Title::class),
            Has::one(Title::class)
        );
    }

    /** @test */
    function producing_a_hasMany()
    {
        self::assertEquals(
            HasMany::ofThe(Title::class),
            Has::many(Title::class)
        );
    }

    /** @test */
    function producing_a_hasOne_with_different_key()
    {
        self::assertEquals(
            HasOne::ofThe(Title::class, In::key('foo')),
            Has::one(Title::class, In::key('foo'))
        );
    }

    /** @test */
    function producing_a_hasMany_with_different_key()
    {
        self::assertEquals(
            HasMany::ofThe(Title::class, In::key('foo')),
            Has::many(Title::class, In::key('foo'))
        );
    }
}
