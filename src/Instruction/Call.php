<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Instruction;

use Closure;
use Stratadox\Hydration\Mapping\Property\Dynamic\ClosureResult;
use Stratadox\HydrationMapper\InstructsHowToMap;
use Stratadox\HydrationMapping\MapsProperty;

/**
 * Indicates that a closure should be called to hydrate this property.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
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
