<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Stub\Constraint;

use Stratadox\Specification\Contract\Specifies;

final class IsBetween
{
    public static function theNumbers(int $low, int $high): Specifies
    {
        return IsHigher::than($low)->and(IsLower::than($high));
    }
}
