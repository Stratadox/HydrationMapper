<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Instruction\Relation;

use Exception;
use Stratadox\Collection\Alterable;
use Stratadox\Hydration\Hydrator\SimpleHydrator;
use Stratadox\Hydration\Hydrator\VariadicConstructor;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyNested;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyProxies;
use Stratadox\Hydration\MapsProperty;
use Stratadox\Hydration\Proxy;
use Stratadox\Hydration\Proxying\AlterableCollectionEntryUpdaterFactory;
use Stratadox\Hydration\Proxying\ArrayEntryUpdaterFactory;
use Stratadox\Hydration\Proxying\ProxyFactory;

final class HasMany extends Relationship
{
    public function followFor(string $property) : MapsProperty
    {
        if ($this->shouldNest) {
            return HasManyNested::inProperty($property,
                VariadicConstructor::forThe($this->container),
                $this->hydrator()
            );
        }
        if ($this->implements(Proxy::class, $this->class)) {
            return HasManyProxies::inProperty($property,
                VariadicConstructor::forThe($this->container),
                ProxyFactory::fromThis(
                    SimpleHydrator::forThe($this->class),
                    $this->loader,
                    $this->implements(Alterable::class, $this->container)
                        ? new AlterableCollectionEntryUpdaterFactory
                        : new ArrayEntryUpdaterFactory
                )
            );
        }
        throw new Exception('Not implementable yet '.$this->class);
    }

    private function implements($interface, $class)
    {
        return (in_array($interface, class_implements($class)));
    }
}
