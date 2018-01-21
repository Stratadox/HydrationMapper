<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Instruction;

use Stratadox\Hydration\Mapper\FindsKeys;
use Stratadox\Hydration\Mapper\InstructsHowToMap;
use Stratadox\Hydration\Mapping\Property\Scalar\StringValue;
use Stratadox\Hydration\MapsProperty;

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
