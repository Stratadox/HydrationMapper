<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Instruction\Relation;

use function class_implements;
use function in_array;
use Stratadox\Collection\Alterable;
use Stratadox\Hydration\Hydrates;
use Stratadox\Hydration\Hydrator\ArrayHydrator;
use Stratadox\Hydration\Hydrator\SimpleHydrator;
use Stratadox\Hydration\Hydrator\VariadicConstructor;
use Stratadox\Hydration\Mapper\NoContainerAvailable;
use Stratadox\Hydration\Mapper\NoLoaderAvailable;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyNested;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyProxies;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneProxy;
use Stratadox\Hydration\MapsProperty;
use Stratadox\Hydration\ProducesOwnerUpdaters;
use Stratadox\Hydration\Proxy;
use Stratadox\Hydration\Proxying\AlterableCollectionEntryUpdaterFactory;
use Stratadox\Hydration\Proxying\ArrayEntryUpdaterFactory;
use Stratadox\Hydration\Proxying\PropertyUpdaterFactory;
use Stratadox\Hydration\Proxying\ProxyFactory;

final class HasMany extends Relationship
{
    public function followFor(string $property) : MapsProperty
    {
        if ($this->shouldNest) {
            return HasManyNested::inPropertyWithDifferentKey($property,
                $this->keyOr($property),
                $this->container(),
                $this->hydrator()
            );
        }
        if (!isset($this->loader)) {
            throw NoLoaderAvailable::for($this->class);
        }
        if ($this->implements(Proxy::class, $this->class)) {
            return HasManyProxies::inPropertyWithDifferentKey($property,
                $this->keyOr($property),
                $this->container(),
                ProxyFactory::fromThis(
                    SimpleHydrator::forThe($this->class),
                    $this->loader,
                    $this->updaterFactory()
                )
            );
        }
        if (!isset($this->container)) {
            throw NoContainerAvailable::for($this->class);
        }
        return HasOneProxy::inProperty($property,
            ProxyFactory::fromThis(
                SimpleHydrator::forThe($this->container),
                $this->loader,
                new PropertyUpdaterFactory
            )
        );
    }

    private function container() : Hydrates
    {
        if (isset($this->container)) {
            return VariadicConstructor::forThe($this->container);
        }
        return ArrayHydrator::create();
    }

    private function updaterFactory() : ProducesOwnerUpdaters
    {
        if ($this->implements(Alterable::class, $this->container)) {
            return new AlterableCollectionEntryUpdaterFactory;
        }
        return new ArrayEntryUpdaterFactory;
    }

    private function implements(string $interface, $class)
    {
        return isset($class) && in_array($interface, class_implements($class));
    }
}
