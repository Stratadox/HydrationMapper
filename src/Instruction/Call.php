<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Instruction;

use Closure;
use Stratadox\Hydration\Mapper\InstructsHowToMap;
use Stratadox\Hydration\Mapping\Property\Dynamic\ClosureResult;
use Stratadox\Hydration\MapsProperty;

class Call implements InstructsHowToMap
{
    private $function;

    public function __construct(Closure $function)
    {
        $this->function = $function;
    }

    public static function the(Closure $function)
    {
        return new Call($function);
    }

    public function followFor(string $property) : MapsProperty
    {
        return ClosureResult::inProperty($property, $this->function);
    }
}
