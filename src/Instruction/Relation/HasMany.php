<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Instruction\Relation;

use function class_implements;
use function in_array;
use Stratadox\Collection\Alterable;
use Stratadox\Hydration\Mapper\NoContainerAvailable;
use Stratadox\Hydration\Mapper\NoLoaderAvailable;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyNested;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyProxies;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneProxy;
use Stratadox\HydrationMapping\MapsProperty;
use Stratadox\Hydrator\ArrayHydrator;
use Stratadox\Hydrator\Hydrates;
use Stratadox\Hydrator\SimpleHydrator;
use Stratadox\Hydrator\VariadicConstructor;
use Stratadox\Proxy\AlterableCollectionEntryUpdaterFactory;
use Stratadox\Proxy\ArrayEntryUpdaterFactory;
use Stratadox\Proxy\ProducesOwnerUpdaters;
use Stratadox\Proxy\PropertyUpdaterFactory;
use Stratadox\Proxy\Proxy;
use Stratadox\Proxy\ProxyFactory;

/**
 * Indicates a polygamic relationship in the property.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
final class HasMany extends Relationship
{
    public function followFor(string $property) : MapsProperty
    {
        if ($this->shouldNest) {
            return $this->manyNestedInThe($property);
        }
        if ($this->isImplementingThe(Proxy::class, $this->class)) {
            return $this->manyProxiesInThe($property);
        }
        return $this->oneProxyInThe($property);
    }

    private function manyNestedInThe(string $property) : MapsProperty
    {
        return HasManyNested::inPropertyWithDifferentKey($property,
            $this->keyOr($property),
            $this->container(),
            $this->hydrator()
        );
    }

    private function manyProxiesInThe(string $property) : MapsProperty
    {
        $this->needsALoader();
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

    private function oneProxyInThe(string $property) : MapsProperty
    {
        $this->needsALoader();
        $this->needsAContainer();
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
        if ($this->isImplementingThe(Alterable::class, $this->container)) {
            return new AlterableCollectionEntryUpdaterFactory;
        }
        return new ArrayEntryUpdaterFactory;
    }

    private function isImplementingThe(string $interface, ?string $class) : bool
    {
        return isset($class) && in_array($interface, class_implements($class));
    }

    private function needsALoader() : void
    {
        if (!isset($this->loader)) {
            throw NoLoaderAvailable::whilstRequiredFor($this->class);
        }
    }

    private function needsAContainer() : void
    {
        if (!isset($this->container)) {
            throw NoContainerAvailable::whilstRequiredFor($this->class);
        }
    }
}
