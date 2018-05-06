<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Instruction;

use Stratadox\Hydration\Mapping\Property\Check;
use Stratadox\Hydration\Mapping\Property\Scalar\OriginalValue;
use Stratadox\HydrationMapper\FindsKeys;
use Stratadox\HydrationMapper\InstructsHowToMap;
use Stratadox\HydrationMapping\MapsProperty;
use Stratadox\Specification\Contract\Satisfiable;

/**
 * Indicates a change in data key.
 *
 * @package Stratadox\Hydrate
 * @author  Stratadox
 */
final class In implements FindsKeys, InstructsHowToMap
{
    private $key;
    private $constraint;

    private function __construct(string $key, ?Satisfiable $constraint)
    {
        $this->key = $key;
        $this->constraint = $constraint;
    }

    /**
     * Indicates that the data for this property can be found in a different key.
     *
     * @param string $key The offset to use in the input array.
     * @return In         The instruction object.
     */
    public static function key(string $key): In
    {
        return new In($key, null);
    }

    /** @inheritdoc */
    public function that(Satisfiable $constraint): InstructsHowToMap
    {
        return new In($this->key, $constraint);
    }

    /** @inheritdoc */
    public function find(): string
    {
        return $this->key;
    }

    /** @inheritdoc */
    public function followFor(string $property): MapsProperty
    {
        $mapping = OriginalValue::inPropertyWithDifferentKey($property, $this->key);
        if (isset($this->constraint)) {
            $mapping = Check::that($this->constraint, $mapping);
        }
        return $mapping;
    }
}
