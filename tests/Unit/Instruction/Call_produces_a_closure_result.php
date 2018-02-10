<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Unit\Instruction;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapper\Instruction\Call;
use Stratadox\Hydration\Mapping\Property\Dynamic\ClosureResult;
use function strlen;
use function var_export;

/**
 * @covers \Stratadox\Hydration\Mapper\Instruction\Call
 */
class Call_produces_a_closure_result extends TestCase
{
    /** @test */
    function producing_a_closure_mapping()
    {
        self::assertEquals(
            ClosureResult::inProperty('version', function ($data) {
                return strlen($data['id']);
            }),
            Call::the(function ($data) {
                return strlen($data['id']);
            })->followFor('version')
        );
    }

    /** @test */
    function producing_another_closure_mapping()
    {
        self::assertEquals(
            ClosureResult::inProperty('data', function ($data) {
                return var_export($data, true);
            }),
            Call::the(function ($data) {
                return var_export($data, true);
            })->followFor('data')
        );
    }
}
