<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper;

interface FindsKeys
{
    public function find() : string;
}
