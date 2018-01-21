<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper;

/**
 * Retrieves the data key.
 *
 * @author Stratadox
 * @package Stratadox\Hydrate
 */
interface FindsKeys
{
    /**
     * Retrieves the data key.
     *
     * @return string The data key for this property
     */
    public function find() : string;
}
