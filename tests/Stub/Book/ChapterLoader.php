<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Stub\Book;

use Stratadox\Proxy\Loader;

class ChapterLoader extends Loader
{
    protected function doLoad($forWhom, string $property, $position = null)
    {
        return; // nah
    }
}
