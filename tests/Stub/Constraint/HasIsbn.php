<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Stub\Constraint;

use Stratadox\Hydration\Mapper\Test\Stub\Book\Isbn;
use Stratadox\Specification\Contract\Specifies;
use Stratadox\Specification\Specification;
use function strlen;

final class HasIsbn extends Specification
{
    private $version;

    private function __construct(int $version)
    {
        $this->version = $version;
    }

    public static function version(int $number): Specifies
    {
        return new HasIsbn($number);
    }

    public function isSatisfiedBy($isbn): bool
    {
        return $isbn instanceof Isbn
            && strlen($isbn->code()) === $this->version;
    }
}
