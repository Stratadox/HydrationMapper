<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Stub\Constraint;

use function is_numeric;
use Stratadox\Specification\Contract\Specifies;
use Stratadox\Specification\Specification;

final class IsHigher extends Specification
{
    private $minimum;

    private function __construct(int $minimum)
    {
        $this->minimum = $minimum;
    }

    public static function than(int $minimum): Specifies
    {
        return new IsHigher($minimum);
    }

    public function isSatisfiedBy($input): bool
    {
        return is_numeric($input) && $input > $this->minimum;
    }
}
