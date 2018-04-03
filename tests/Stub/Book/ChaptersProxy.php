<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Stub\Book;

use Stratadox\Collection\Alterable;
use Stratadox\Proxy\Proxy;
use Stratadox\Proxy\Proxying;

class ChaptersProxy extends Chapters implements Proxy
{
    use Proxying;

    public function items(): array
    {
        return $this->__load()->items();
    }

    public function change(int $index, $newItem): Alterable
    {
        return $this->__load()->change($index, $newItem);
    }

    public function current(): Chapter
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

    public function rewind(): void
    {
        $this->__load()->rewind();
    }

    public function __toString(): string
    {
        return $this->__load()->__toString();
    }

    public function count(): int
    {
        return $this->__load()->count();
    }

    public function toArray(): array
    {
        return $this->__load()->toArray();
    }

    public function getSize(): int
    {
        return $this->__load()->getSize();
    }

    public function offsetExists($index): bool
    {
        return $this->__load()->offsetExists($index);
    }

    public function offsetGet($index): Chapter
    {
        return $this->__load()->offsetGet($index);
    }
}
