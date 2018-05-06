<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Instruction\Relation;

use Stratadox\Hydration\Mapping\Property\Relationship\HasOneEmbedded;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneNested;
use Stratadox\HydrationMapping\MapsProperty;

/**
 * Indicates a monogamous relationship in the property.
 *
 * @package Stratadox\Hydrate
 * @author  Stratadox
 */
final class HasOne extends Relationship
{
    /** @inheritdoc */
    public function followFor(string $property): MapsProperty
    {
        if ($this->shouldNest) {
            return $this->addConstraintTo(
                HasOneNested::inPropertyWithDifferentKey(
                    $property,
                    $this->keyOr($property),
                    $this->hydrator()
                )
            );
        }
        return $this->addConstraintTo(HasOneEmbedded::inProperty(
            $property,
            $this->hydrator()
        ));
    }
}
