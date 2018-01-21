<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Stub;

use Stratadox\Hydration\LoadsProxiedObjects;
use Stratadox\Hydration\ProducesProxyLoaders;

class ChapterLoaderFactory implements ProducesProxyLoaders
{
    public function makeLoaderFor(
        $theOwner,
        string $ofTheProperty,
        $atPosition = null
    ) : LoadsProxiedObjects
    {
        return new ChapterLoader(null, '');
    }
}
