<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Instruction;

use Stratadox\Hydration\Mapping\Property\Scalar\StringValue;
use Stratadox\HydrationMapper\FindsKeys;
use Stratadox\HydrationMapper\InstructsHowToMap;
use Stratadox\HydrationMapping\MapsProperty;

/**
 * Indicates a change in data key.
 *
 * @package Stratadox\Hydrate
 * @author  Stratadox
 */
final class In implements FindsKeys, InstructsHowToMap
{
    private $key;

    private function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * Indicates that the data for this property can be found in a different key.
     *
     * @param string $key The offset to use in the input array.
     * @return In         The instruction object.
     */
    public static function key(string $key): In
    {
        return new In($key);
    }

    /** @inheritdoc */
    public function find(): string
    {
        return $this->key;
    }

    /** @inheritdoc */
    public function followFor(string $property): MapsProperty
    {
        return StringValue::inPropertyWithDifferentKey($property, $this->key);
    }
}
