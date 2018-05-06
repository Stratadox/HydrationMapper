<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Stub\Constraint;

use function count;
use Stratadox\Specification\Contract\Specifies;
use Stratadox\Specification\Specification;

final class ContainsMore extends Specification
{
    private $moreThan;

    private function __construct(int $moreThan)
    {
        $this->moreThan = $moreThan;
    }

    public static function than(int $thisManyItems): Specifies
    {
        return new ContainsMore($thisManyItems);
    }

    public function isSatisfiedBy($object): bool
    {
        return count($object) > $this->moreThan;
    }
}
