<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Stub\Constraint;

use function in_array;
use Stratadox\Specification\Contract\Specifies;
use Stratadox\Specification\Specification;

final class IsEither extends Specification
{
    private $options;

    private function __construct(array $options)
    {
        $this->options = $options;
    }

    public static function oneOf(...$value): Specifies
    {
        return new IsEither($value);
    }

    public function isSatisfiedBy($object): bool
    {
        return in_array($object, $this->options, true);
    }
}
