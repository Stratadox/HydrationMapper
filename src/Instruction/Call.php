<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Instruction;

use Closure;
use Stratadox\Hydration\Mapping\Property\Check;
use Stratadox\Hydration\Mapping\Property\Dynamic\ClosureResult;
use Stratadox\HydrationMapper\InstructsHowToMap;
use Stratadox\HydrationMapping\MapsProperty;
use Stratadox\Specification\Contract\Satisfiable;

/**
 * Indicates that a closure should be called to hydrate this property.
 *
 * @package Stratadox\Hydrate
 * @author  Stratadox
 */
final class Call implements InstructsHowToMap
{
    private $function;
    private $constraint;

    private function __construct(Closure $function, ?Satisfiable $constraint)
    {
        $this->function = $function;
        $this->constraint = $constraint;
    }

    /**
     * Creates a closure-call instruction for the property.
     *
     * @param Closure $function The anonymous function to call while hydrating
     *                          this property.
     * @return Call             The instruction object.
     */
    public static function the(Closure $function): Call
    {
        return new Call($function, null);
    }

    /** @inheritdoc */
    public function that(Satisfiable $constraint): InstructsHowToMap
    {
        return new Call($this->function, $constraint);
    }

    /** @inheritdoc */
    public function followFor(string $property): MapsProperty
    {
        $mapping = ClosureResult::inProperty($property, $this->function);
        if (isset($this->constraint)) {
            $mapping = Check::that($this->constraint, $mapping);
        }
        return $mapping;
    }
}
