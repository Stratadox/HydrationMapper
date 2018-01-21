<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Unit\Instruction;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapper\Instruction\Has;
use Stratadox\Hydration\Mapper\Instruction\Relation\HasMany;
use Stratadox\Hydration\Mapper\Instruction\Relation\HasOne;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Title;

/**
 * @covers \Stratadox\Hydration\Mapper\Instruction\Has
 */
class Has_indicates_a_relationship extends TestCase
{
    /** @scenario */
    function producing_a_hasOne()
    {
        self::assertEquals(
            HasOne::ofThe(Title::class),
            Has::one(Title::class)
        );
    }

    /** @scenario */
    function producing_a_hasMany()
    {
        self::assertEquals(
            HasMany::ofThe(Title::class),
            Has::many(Title::class)
        );
    }
}
