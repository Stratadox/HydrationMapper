<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Stub\Instruction;

use Stratadox\Hydration\Mapping\Property\Scalar\IntegerValue;
use Stratadox\HydrationMapper\InstructsHowToMap;
use Stratadox\HydrationMapping\MapsProperty;

class ItsANumber implements InstructsHowToMap
{
    public static function allRight(): InstructsHowToMap
    {
        return new self;
    }

    public function followFor(string $property): MapsProperty
    {
        return IntegerValue::inProperty($property);
    }
}
