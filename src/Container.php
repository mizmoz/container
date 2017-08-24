<?php

namespace Mizmoz\Container;

use Closure;
use Mizmoz\Container\Contract\ContainerInterface;
use Mizmoz\Container\Contract\ResolverInterface;
use Mizmoz\Container\Contract\ServiceProviderInterface;
use Mizmoz\Container\Exception\FatalNotFoundException;
use Mizmoz\Container\Exception\InvalidEntryException;
use Mizmoz\Container\Exception\NotFoundException;

class Container implements ContainerInterface
{
    /**
     * Registry for the items
     *
     * @var array
     */
    private $registry = [];

    /**
     * @var ResolverInterface
     */
    private $resolver;

    /**
     * Container constructor.
     * @param ResolverInterface|null $resolver
     */
    public function __construct(ResolverInterface $resolver = null)
    {
        $this->resolver = $resolver;
    }

    /**
     * Find the item in the registry
     *
     * @param $id
     * @return mixed
     */
    private function find($id)
    {
        if (! isset($this->registry[$id])) {
            throw new NotFoundException("Entry id '" . $id . "' not found in container");
        }

        return $this->registry[$id];
    }

    /**
     * @inheritdoc
     */
    public function injectContainer($entry)
    {
        return InjectContainer::inject($this, $entry);
    }

    /**
     * @inheritDoc
     */
    public function get($id)
    {
        try {
            return $this->injectContainer($this->find($id)($id, $this));
        } catch (NotFoundException $e) {
            if (! $this->resolver || $e instanceof FatalNotFoundException) {
                // no resolver or fatal so throw the original exception
                throw $e;
            }

            // resolve and add to the container
            $this->add($id, function () use ($id) {
                return $this->resolver->resolve($id, $this);
            });

            // fetch the item from the container
            return $this->get($id);
        }
    }

    /**
     * @inheritDoc
     */
    public function has($id)
    {
        return isset($this->registry[$id]);
    }

    /**
     * @inheritdoc
     */
    public function add(string $id, $entry, string $type = self::EXCLUSIVE): Entry
    {
        if (is_string($entry)) {
            $entry = function () use ($entry) {
                return ($this->resolver ? $this->resolver->resolve($entry, $this) : new $entry);
            };
        }

        if (! ($entry instanceof Closure || is_callable($entry))) {
            throw new InvalidEntryException('$entry must be callable or MyClass::class string');
        }

        return $this->registry[$id] = new Entry(
            $id, $entry, $type
        );
    }

    /**
     * @inheritdoc
     */
    public function addAlias(string $id, string $alias): ContainerInterface
    {
        $this->registry[$alias] = $this->find($id);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addShared(string $id, $entry): Entry
    {
        return $this->add($id, $entry, self::SHARED);
    }

    /**
     * @inheritdoc
     */
    public function addExclusive(string $id, $entry): Entry
    {
        return $this->add($id, $entry, self::EXCLUSIVE);
    }

    /**
     * @inheritDoc
     */
    public function addValue(string $id, $value): Entry
    {
        return $this->addShared($id, function () use ($value) {
            return $value;
        });
    }

    /**
     * @inheritdoc
     */
    public function addServiceProvider(ServiceProviderInterface $serviceProvider): Provider
    {
        // boot the service provider
        $serviceProvider->boot($this);

        // create the provider which will handle registering the entries when they're needed
        $provider = new Provider($serviceProvider);

        // loop over the provided entries and add to the registry
        foreach ($serviceProvider->provides() as $id) {
            $this->registry[$id] = $provider;
        }

        return $provider;
    }
}