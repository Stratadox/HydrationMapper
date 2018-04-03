<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Test\Stub\Book;

class Image implements Element
{
    private $url;
    private $alt;

    public function __construct(string $url, string $alt)
    {
        $this->url = $url;
        $this->alt = $alt;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function __toString(): string
    {
        return $this->alt;
    }
}
