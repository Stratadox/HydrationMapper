<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Stub\Constraint;

use const FILTER_VALIDATE_URL;
use function filter_var;
use Stratadox\Hydration\Mapper\Test\Stub\Book\Image;
use Stratadox\Specification\Specification;

final class HasValidUrl extends Specification
{
    public static function property(): self
    {
        return new self();
    }

    public function isSatisfiedBy($element): bool
    {
        return $element instanceof Image
            && filter_var($element->url(), FILTER_VALIDATE_URL) !== false;
    }
}
