<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Stub\Constraint;

use function is_numeric;
use Stratadox\Specification\Contract\Specifies;
use Stratadox\Specification\Specification;

final class IsLower extends Specification
{
    private $maximum;

    private function __construct(int $maximum)
    {
        $this->maximum = $maximum;
    }

    public static function than(int $maximum): Specifies
    {
        return new IsLower($maximum);
    }

    public function isSatisfiedBy($input): bool
    {
        return is_numeric($input) && $input < $this->maximum;
    }
}
