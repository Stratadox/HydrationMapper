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
final class Call implements InstructsHowToMap
{
    private $function;

    private function __construct(Closure $function)
    {
        $this->function = $function;
    }

    /**
     * Create a closure call instruction for the property.
     *
     * @param Closure $function The anonymous function to call while hydrating
     *                          this property.
     * @return Call             The instruction object.
     */
    public static function the(Closure $function): Call
    {
        return new Call($function);
    }

    public function followFor(string $property): MapsProperty
    {
        return ClosureResult::inProperty($property, $this->function);
    }
}
