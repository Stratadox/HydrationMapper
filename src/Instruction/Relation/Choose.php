<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Instruction\Relation;

use Stratadox\Hydration\Hydrates;
use Stratadox\Hydration\Mapper\InstructsHowToMap;
use Stratadox\Hydration\Mapper\MakesMap;
use Stratadox\Hydration\Mapper\Mapper;
use Stratadox\Hydration\Mapper\RepresentsChoice;

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

    public function hydrator() : Hydrates
    {
        return $this->class->hydrator();
    }
}
