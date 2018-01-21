<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Stub\Book;

use Stratadox\Collection\Alterable;
use Stratadox\Hydration\Proxy;
use Stratadox\Hydration\Proxying\Proxying;

class ChaptersProxy extends Chapters implements Proxy
{
    use Proxying;

    public function __construct()
    {
    }

    public function items() : array
    {
        return $this->__load()->items();
    }

    public function change(int $index, $newItem) : Alterable
    {
        return $this->__load()->change($index, $newItem);
    }

    public function current() : Chapter
    {
        return $this->__load()->current();
    }

    public function next()
    {
        return $this->__load()->next();
    }

    public function key()
    {
        return $this->__load()->key();
    }

    public function valid()
    {
        return $this->__load()->valid();
    }

    public function rewind()
    {
        return $this->__load()->rewind();
    }

    public function __toString() : string
    {
        return $this->__load()->__toString();
    }

    public function count()
    {
        return $this->__load()->count();
    }

    public function toArray()
    {
        return $this->__load()->toArray();
    }

    public function getSize()
    {
        return $this->__load()->getSize();
    }

    public function offsetExists($index)
    {
        return $this->__load()->offsetExists($index);
    }

    public function offsetGet($index)
    {
        return $this->__load()->offsetGet($index);
    }
}
