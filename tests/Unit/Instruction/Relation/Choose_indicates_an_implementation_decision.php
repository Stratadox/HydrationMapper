<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Unit\Instruction\Relation;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Hydrator\MappedHydrator;
use Stratadox\Hydration\Mapper\Instruction\Relation\Choose;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Text;
use Stratadox\Hydration\Mapper\Test\Stub\Instruction\ItsANumber;
use Stratadox\Hydration\Mapping\Properties;
use Stratadox\Hydration\Mapping\Property\Scalar\IntegerValue;
use Stratadox\Hydration\Mapping\Property\Scalar\StringValue;

/**
 * @covers \Stratadox\Hydration\Mapper\Instruction\Relation\Choose
 */
class Choose_indicates_an_implementation_decision extends TestCase
{
    /** @scenario */
    function building_a_hydrator()
    {
        self::assertEquals(
            MappedHydrator::forThe(Text::class, Properties::map(
                IntegerValue::inProperty('id'),
                StringValue::inProperty('text')
            )),
            Choose::the(Text::class)
                ->with('id', ItsANumber::allRight())
                ->with('text')
                ->finish()
        );
    }
}
