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
 * @author Stratadox
 */
final class In implements FindsKeys, InstructsHowToMap
{
    private $key;

    private function __construct($key)
    {
        $this->key = $key;
    }

    public static function key(string $key)
    {
        return new In($key);
    }

    public function find() : string
    {
        return $this->key;
    }

    public function followFor(string $property) : MapsProperty
    {
        return StringValue::inPropertyWithDifferentKey($property, $this->key);
    }
}
