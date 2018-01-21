<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Stub;

use Stratadox\Hydration\Proxy;
use Stratadox\Hydration\Proxying\Proxying;

class ChapterProxy extends Chapter implements Proxy
{
    use Proxying;

    public function title() : Title
    {
        return $this->__load()->title();
    }

    public function text() : Text
    {
        return $this->__load()->text();
    }

    public function __toString() : string
    {
        return $this->__load()->__toString();
    }
}
