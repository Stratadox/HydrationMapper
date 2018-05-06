<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Stub\Constraint;

use function is_iterable;
use Stratadox\Specification\Contract\Satisfiable;
use Stratadox\Specification\Contract\Specifies;
use Stratadox\Specification\Specification;

final class OnlyHasElements extends Specification
{
    private $constraint;

    public function __construct(Satisfiable $constraint)
    {
        $this->constraint = $constraint;
    }

    public static function that(Satisfiable $constraint): Specifies
    {
        return new OnlyHasElements($constraint);
    }

    public function isSatisfiedBy($object): bool
    {
        if (!is_iterable($object)) {
            return false;
        }
        foreach ($object as $value) {
            if (!$this->constraint->isSatisfiedBy($value)) {
                return false;
            }
        }
        return true;
    }
}
