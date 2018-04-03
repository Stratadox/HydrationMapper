<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapper\Instruction\Relation;

use function class_exists;
use function class_implements;
use function in_array;
use ReflectionException;
use Stratadox\Collection\Alterable;
use Stratadox\Hydration\Mapper\NoContainerAvailable;
use Stratadox\Hydration\Mapper\NoLoaderAvailable;
use Stratadox\Hydration\Mapper\NoSuchClass;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyNested;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyProxies;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneProxy;
use Stratadox\HydrationMapper\InvalidMapperConfiguration;
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
    /** @inheritdoc */
    public function followFor(string $property): MapsProperty
    {
        try {
            if ($this->shouldNest) {
                return $this->manyNestedInThe($property);
            }
            if ($this->isImplementingThe(Proxy::class, $this->class)) {
                return $this->manyProxiesInThe($property);
            }
            return $this->oneProxyInThe($property);
        } catch (ReflectionException $encounteredException) {
            throw NoSuchClass::couldNotLoad($this->class);
        }
    }

    /**
     * Maps an eagerly loaded collection from a nested data set.
     *
     * @param string $property The property that gets a nested eager relationship.
     * @return MapsProperty    The resulting property mapping.
     * @throws InvalidMapperConfiguration
     */
    private function manyNestedInThe(string $property): MapsProperty
    {
        return HasManyNested::inPropertyWithDifferentKey(
            $property,
            $this->keyOr($property),
            $this->container(),
            $this->hydrator()
        );
    }

    /**
     * Maps an extra lazily loaded collection as list of proxies.
     *
     * @param string $property      The property that gets an extra lazy relationship.
     * @return MapsProperty         The resulting property mapping.
     * @throws ReflectionException  When the class does not exist.
     */
    private function manyProxiesInThe(string $property): MapsProperty
    {
        if (!isset($this->loader)) {
            throw NoLoaderAvailable::whilstRequiredFor($this->class);
        }
        return HasManyProxies::inPropertyWithDifferentKey(
            $property,
            $this->keyOr($property),
            $this->container(),
            ProxyFactory::fromThis(
                SimpleHydrator::forThe($this->class),
                $this->loader,
                $this->updaterFactory()
            )
        );
    }

    /**
     * Maps a lazily loaded collection as a single proxy.
     *
     * @param string $property      The property that gets a lazy relationship.
     * @return MapsProperty         The resulting property mapping.
     * @throws ReflectionException  When the class does not exist.
     */
    private function oneProxyInThe(string $property): MapsProperty
    {
        if (!isset($this->loader)) {
            throw NoLoaderAvailable::whilstRequiredFor($this->class);
        }
        if (!isset($this->container)) {
            throw NoContainerAvailable::whilstRequiredFor($this->class);
        }
        return HasOneProxy::inProperty($property,
            ProxyFactory::fromThis(
                SimpleHydrator::forThe($this->container),
                $this->loader,
                new PropertyUpdaterFactory
            )
        );
    }

    /**
     * @return Hydrates The hydrator for the collection container.
     */
    private function container(): Hydrates
    {
        if (isset($this->container)) {
            return VariadicConstructor::forThe($this->container);
        }
        return ArrayHydrator::create();
    }

    /**
     * @return ProducesOwnerUpdaters The relevant updater factory.
     */
    private function updaterFactory(): ProducesOwnerUpdaters
    {
        if ($this->isImplementingThe(Alterable::class, $this->container)) {
            return new AlterableCollectionEntryUpdaterFactory;
        }
        return new ArrayEntryUpdaterFactory;
    }

    /**
     * @param string $interface  The interface name.
     * @param null|string $class The class name.
     * @return bool              Whether the class implements the interface.
     */
    private function isImplementingThe(string $interface, ?string $class): bool
    {
        return isset($class) && class_exists($class) && in_array($interface, class_implements($class));
    }
}
