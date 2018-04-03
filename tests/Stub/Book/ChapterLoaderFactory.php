<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Stub\Book;

use Stratadox\Proxy\LoadsProxiedObjects;
use Stratadox\Proxy\ProducesProxyLoaders;

class ChapterLoaderFactory implements ProducesProxyLoaders
{
    public function makeLoaderFor(
        $theOwner,
        string $ofTheProperty,
        $atPosition = null
    ): LoadsProxiedObjects {
        return new ChapterLoader($theOwner, $ofTheProperty);
    }
}
