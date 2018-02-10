<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Instruction\Relation;

use Stratadox\Hydration\Mapper\Mapper;
use Stratadox\HydrationMapper\InstructsHowToMap;
use Stratadox\HydrationMapper\MakesMap;
use Stratadox\HydrationMapper\RepresentsChoice;
use Stratadox\Hydrator\Hydrates;

/**
 * Indicates a choice for one of several concrete types.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
final class Choose implements RepresentsChoice
{
    private $class;

    private function __construct(MakesMap $class)
    {
        $this->class = $class;
    }

    public static function the(string $class) : RepresentsChoice
    {
        return new Choose(Mapper::forThe($class));
    }

    public function with(string $property, InstructsHowToMap $howToMap = null) : RepresentsChoice
    {
        $this->class = $this->class->property($property, $howToMap);
        return $this;
    }

    public function finish() : Hydrates
    {
        return $this->class->finish();
    }
}
