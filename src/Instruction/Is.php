<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Instruction;

use function call_user_func;
use function sprintf;
use Stratadox\Hydration\Mapper\InstructsHowToMap;
use Stratadox\Hydration\Mapping\Property\Scalar\BooleanValue;
use Stratadox\Hydration\Mapping\Property\Scalar\FloatValue;
use Stratadox\Hydration\Mapping\Property\Scalar\IntegerValue;
use Stratadox\Hydration\Mapping\Property\Scalar\StringValue;
use Stratadox\Hydration\MapsProperty;

final class Is implements InstructsHowToMap
{
    private const SAME_KEY = 'inProperty';
    private const USE_KEY = 'inPropertyWithDifferentKey';

    private $className;
    private $constructionMethod;
    private $key;

    public function __construct(
        string $className,
        string $constructionMethod,
        ?string $key
    ) {
        $this->className = $className;
        $this->constructionMethod = $constructionMethod;
        $this->key = $key;
    }

    public static function bool() : InstructsHowToMap
    {
        return Is::type(BooleanValue::class);
    }

    public static function float() : InstructsHowToMap
    {
        return Is::type(FloatValue::class);
    }

    public static function int() : InstructsHowToMap
    {
        return Is::type(IntegerValue::class);
    }

    public static function string() : InstructsHowToMap
    {
        return Is::type(StringValue::class);
    }

    public static function boolInKey(string $key) : InstructsHowToMap
    {
        return Is::type(BooleanValue::class, self::USE_KEY, $key);
    }

    public static function floatInKey(string $key) : InstructsHowToMap
    {
        return Is::type(FloatValue::class, self::USE_KEY, $key);
    }

    public static function intInKey(string $key) : InstructsHowToMap
    {
        return Is::type(IntegerValue::class, self::USE_KEY, $key);
    }

    public static function stringInKey(string $key) : InstructsHowToMap
    {
        return Is::type(StringValue::class, self::USE_KEY, $key);
    }

    private static function type(
        string $className,
        string $constructor = self::SAME_KEY,
        string $key = null
    ) : InstructsHowToMap
    {
        return new Is($className, $constructor, $key);
    }

    public function followFor(string $property) : MapsProperty
    {
        return call_user_func(
            sprintf('%s::%s', $this->className, $this->constructionMethod),
            $property, $this->key
        );
    }
}
