<?php

namespace Mizmoz\Container;

use Mizmoz\Container\Contract\ContainerInterface;
use Mizmoz\Container\Contract\ServiceProviderInterface;
use Mizmoz\Container\Exception\ServiceProviderException;

class ProviderContainer extends Container
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var ServiceProviderInterface
     */
    private $serviceProvider;

    /**
     * Keep a list of the registered items.
     *
     * @var array
     */
    private $registered = [];

    /**
     * ProviderContainer constructor.
     * @param ContainerInterface $container
     * @param ServiceProviderInterface $serviceProvider
     */
    public function __construct(ContainerInterface $container, ServiceProviderInterface $serviceProvider)
    {
        $this->container = $container;
        $this->serviceProvider = $serviceProvider;
    }

    /**
     * @inheritdoc
     */
    public function injectContainer($entry)
    {
        return $this->container->injectContainer($entry);
    }

    /**
     * @inheritDoc
     */
    public function get($id)
    {
        return $this->container->get($id);
    }

    /**
     * @inheritDoc
     */
    public function has($id)
    {
        return $this->container->has($id);
    }

    /**
     * Override so we can keep track of the provided items.
     *
     * @inheritdoc
     */
    public function add(string $id, $entry, string $type = self::EXCLUSIVE): Entry
    {
        $this->registered[] = $id;
        return $this->container->add($id, $entry, $type);
    }

    /**
     * Override so we can keep track of the provided items.
     *
     * @inheritdoc
     */
    public function addAlias(string $id, string $alias): ContainerInterface
    {
        $this->registered[] = $alias;
        return $this->container->addAlias($id, $alias);
    }

    /**
     * @inheritdoc
     */
    public function addShared(string $id, $entry): Entry
    {
        return $this->container->addShared($id, $entry);
    }

    /**
     * @inheritdoc
     */
    public function addExclusive(string $id, $entry): Entry
    {
        return $this->container->addExclusive($id, $entry);
    }

    /**
     * @inheritdoc
     */
    public function addValue(string $id, $entry): Entry
    {
        return $this->container->addValue($id, $entry);
    }

    /**
     * @inheritdoc
     */
    public function addServiceProvider(ServiceProviderInterface $serviceProvider): Provider
    {
        return $this->container->addServiceProvider($serviceProvider);
    }

    /**
     * Check all the items that were promised were actually registered
     */
    public function done()
    {
        if (array_diff($this->registered, $this->serviceProvider->provides())) {
            throw new ServiceProviderException(
                get_class($this->serviceProvider) . ' said it would provide: "' .
                join(', ', $this->serviceProvider->provides()) . '" but ended up providing "' .
                join(', ', $this->registered) . '"'
            );
        }
    }

    /**
     * Get the container
     *
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * Pass all calls to the original container
     *
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->container, $name], $arguments);
    }
}