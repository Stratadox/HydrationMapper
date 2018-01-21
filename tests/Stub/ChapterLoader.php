<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Stub;

use Stratadox\Hydration\Proxying\Loader;

class ChapterLoader extends Loader
{
    protected function doLoad($forWhom, string $property, $position = null)
    {
        return; // nah
    }
}
