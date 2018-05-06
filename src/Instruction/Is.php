<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Instruction;

use function array_merge;
use Stratadox\Hydration\Mapping\Property\Check;
use Stratadox\Hydration\Mapping\Property\Scalar\BooleanValue;
use Stratadox\Hydration\Mapping\Property\Scalar\CanBeFloat;
use Stratadox\Hydration\Mapping\Property\Scalar\CanBeInteger;
use Stratadox\Hydration\Mapping\Property\Scalar\CanBeNull;
use Stratadox\Hydration\Mapping\Property\Scalar\FloatValue;
use Stratadox\Hydration\Mapping\Property\Scalar\IntegerValue;
use Stratadox\Hydration\Mapping\Property\Scalar\OriginalValue;
use Stratadox\Hydration\Mapping\Property\Scalar\StringValue;
use Stratadox\HydrationMapper\DefinesTheType;
use Stratadox\HydrationMapper\InstructsHowToMap;
use Stratadox\HydrationMapping\MapsProperty;
use function call_user_func;
use function sprintf;
use Stratadox\Specification\Contract\Satisfiable;

/**
 * Indicates the type of a property, optionally changing the data key.
 *
 * @package Stratadox\Hydrate
 * @author  Stratadox
 */
final class Is implements DefinesTheType
{
    private const SAME_KEY = 'inProperty';
    private const USE_KEY = 'inPropertyWithDifferentKey';

    private $className;
    private $constructorName;
    private $decorators;
    private $key;
    private $isValid;

    private function __construct(
        string $className,
        string $constructorName,
        array $decorators,
        ?string $key
    ) {
        $this->className = $className;
        $this->constructorName = $constructorName;
        $this->decorators = $decorators;
        $this->key = $key;
    }

    public static function bool(): DefinesTheType
    {
        return Is::type(BooleanValue::class);
    }

    public static function float(): DefinesTheType
    {
        return Is::type(FloatValue::class);
    }

    public static function int(): DefinesTheType
    {
        return Is::type(IntegerValue::class);
    }

    public static function string(): DefinesTheType
    {
        return Is::type(StringValue::class);
    }

    public static function boolInKey(string $key): DefinesTheType
    {
        return Is::type(BooleanValue::class, self::USE_KEY, $key);
    }

    public static function floatInKey(string $key): DefinesTheType
    {
        return Is::type(FloatValue::class, self::USE_KEY, $key);
    }

    public static function intInKey(string $key): DefinesTheType
    {
        return Is::type(IntegerValue::class, self::USE_KEY, $key);
    }

    public static function stringInKey(string $key): DefinesTheType
    {
        return Is::type(StringValue::class, self::USE_KEY, $key);
    }

    public static function unchanged(): DefinesTheType
    {
        return Is::type(OriginalValue::class);
    }

    public static function number(): DefinesTheType
    {
        return Is::decoratedType(FloatValue::class, self::SAME_KEY, [
            CanBeInteger::class . '::or',
        ]);
    }

    public static function numberInKey(string $key): DefinesTheType
    {
        return Is::decoratedType(FloatValue::class, self::USE_KEY, [
            CanBeInteger::class . '::or',
        ], $key);
    }

    public static function mixed(): DefinesTheType
    {
        return Is::decoratedType(StringValue::class, self::SAME_KEY, [
            CanBeFloat::class . '::or',
            CanBeInteger::class . '::or',
            CanBeNull::class . '::or',
        ]);
    }

    public static function mixedInKey(string $key): DefinesTheType
    {
        return Is::decoratedType(StringValue::class, self::USE_KEY, [
            CanBeFloat::class . '::or',
            CanBeInteger::class . '::or',
            CanBeNull::class . '::or',
        ], $key);
    }

    /**
     * Declare that the property is of the type.
     *
     * @param string      $className   Class name of the property mapping.
     * @param string      $constructor Constructor name to use.
     * @param string|null $key         Data key to use.
     * @return DefinesTheType       The mapping instruction.
     */
    private static function type(
        string $className,
        string $constructor = self::SAME_KEY,
        string $key = null
    ): DefinesTheType {
        return new Is($className, $constructor, [], $key);
    }

    /**
     * Declare that the property is of the decorated type.
     *
     * @param string      $className   Class name of the property mapping.
     * @param string      $constructor Constructor name to use.
     * @param string[]    $decorators  Decorator full constructor names.
     * @param string|null $key         Data key to use.
     * @return DefinesTheType       The mapping instruction.
     */
    private static function decoratedType(
        string $className,
        string $constructor,
        array $decorators,
        string $key = null
    ): DefinesTheType {
        return new Is($className, $constructor, $decorators, $key);
    }

    /** @inheritdoc */
    public function followFor(string $property): MapsProperty
    {
        $mapping = call_user_func(
            sprintf('%s::%s', $this->className, $this->constructorName),
            $property, $this->key
        );
        foreach ($this->decorators as $decorated) {
            $mapping = $decorated($mapping);
        }
        if (isset($this->isValid)) {
            $mapping = Check::that($this->isValid, $mapping);
        }
        return $mapping;
    }

    /** @inheritdoc */
    public function nullable(): DefinesTheType
    {
        return Is::decoratedType(
            $this->className, 
            $this->constructorName,
            array_merge($this->decorators, [CanBeNull::class . '::or']),
            $this->key
        );
    }

    /** @inheritdoc */
    public function that(Satisfiable $constraint): InstructsHowToMap
    {
        $new = clone $this;
        $new->isValid = $constraint;
        return $new;
    }
}
