<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Stub\Constraint;

use Stratadox\Specification\Contract\Specifies;
use Stratadox\Specification\Specification;

final class IsNotEmpty extends Specification
{
    private function __construct() {}

    public static function text(): Specifies
    {
        return new IsNotEmpty;
    }

    public function isSatisfiedBy($object): bool
    {
        return (string) $object !== '';
    }
}
