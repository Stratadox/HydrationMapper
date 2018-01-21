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
    private $className;
    private $constructionMethod;

    public function __construct($className, $constructionMethod)
    {
        $this->className = $className;
        $this->constructionMethod = $constructionMethod;
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

    private static function type(
        string $className,
        string $constructor = 'inProperty'
    ) : InstructsHowToMap
    {
        return new Is($className, $constructor);
    }

    public function followFor(string $property) : MapsProperty
    {
        return call_user_func(
            sprintf('%s::%s', $this->className, $this->constructionMethod),
            $property
        );
    }
}
