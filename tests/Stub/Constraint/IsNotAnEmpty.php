<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Stub\Constraint;

use function count;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Chapter;
use Stratadox\Specification\Contract\Specifies;
use Stratadox\Specification\Specification;

final class IsNotAnEmpty extends Specification
{
    public static function chapter(): Specifies
    {
        return new IsNotAnEmpty;
    }

    public function isSatisfiedBy($object): bool
    {
        return $object instanceof Chapter
            && count($object->elements()) > 0;
    }
}
